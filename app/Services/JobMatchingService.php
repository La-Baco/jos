<?php

namespace App\Services;

use App\Models\Job;
use App\Models\JobRecommendation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class JobMatchingService
{
    /**
     * Weights configuration (Total = 1.00 / 100%).
     */
    public const WEIGHT_SKILL = 0.30;

    public const WEIGHT_EDUCATION = 0.20;

    public const WEIGHT_LOCATION = 0.20;

    public const WEIGHT_EXPERIENCE = 0.15;

    public const WEIGHT_INTEREST = 0.10;

    public const WEIGHT_AGE = 0.05;

    /**
     * Compute matching score and detailed diagnostics for a user and a job.
     *
     * @return array{
     *     total_score: int,
     *     skill_score: int,
     *     education_score: int,
     *     location_score: int,
     *     experience_score: int,
     *     interest_score: int,
     *     age_score: int,
     *     breakdown: array<string, mixed>
     * }
     */
    public function match(User $user, Job $job): array
    {
        $skillResult = $this->calculateSkillScore($user, $job);
        $educationResult = $this->calculateEducationScore($user, $job);
        $locationResult = $this->calculateLocationScore($user, $job);
        $experienceResult = $this->calculateExperienceScore($user, $job);
        $interestResult = $this->calculateInterestScore($user, $job);
        $ageResult = $this->calculateAgeScore($user, $job);

        $finalScore = (
            ($skillResult['score'] * self::WEIGHT_SKILL) +
            ($educationResult['score'] * self::WEIGHT_EDUCATION) +
            ($locationResult['score'] * self::WEIGHT_LOCATION) +
            ($experienceResult['score'] * self::WEIGHT_EXPERIENCE) +
            ($interestResult['score'] * self::WEIGHT_INTEREST) +
            ($ageResult['score'] * self::WEIGHT_AGE)
        );

        $roundedFinalScore = (int) round($finalScore);
        $roundedFinalScore = max(0, min(100, $roundedFinalScore));

        return [
            'total_score' => $roundedFinalScore,
            'skill_score' => $skillResult['score'],
            'education_score' => $educationResult['score'],
            'location_score' => $locationResult['score'],
            'experience_score' => $experienceResult['score'],
            'interest_score' => $interestResult['score'],
            'age_score' => $ageResult['score'],
            'breakdown' => [
                'skill' => $skillResult,
                'education' => $educationResult,
                'location' => $locationResult,
                'experience' => $experienceResult,
                'interest' => $interestResult,
                'age' => $ageResult,
            ],
        ];
    }

    /**
     * 1. Calculate Skill Score (Weight 30%)
     * Rule: Exact match + alias dictionary.
     */
    protected function calculateSkillScore(User $user, Job $job): array
    {
        $jobSkills = $job->skills;

        if ($jobSkills->isEmpty()) {
            return [
                'score' => 85,
                'percentage' => 85,
                'weighted_score' => round(85 * self::WEIGHT_SKILL, 1),
                'matched_count' => 0,
                'total_required' => 0,
                'matched_skills' => [],
                'missing_skills' => [],
                'explanation' => 'Lowongan ini tidak mensyaratkan skill spesifik.',
            ];
        }

        // Get user skills and their alias names
        $userSkills = $user->skills()->with('aliases')->get();
        $userSkillIds = $userSkills->pluck('id')->toArray();

        // Also gather all alias strings for user skills
        $userAliasTerms = [];
        foreach ($userSkills as $us) {
            $userAliasTerms[] = strtolower($us->name);
            foreach ($us->aliases as $alias) {
                $userAliasTerms[] = strtolower($alias->alias);
            }
        }
        $userAliasTerms = array_unique($userAliasTerms);

        $matchedSkills = [];
        $missingSkills = [];

        foreach ($jobSkills as $js) {
            $isMatched = false;

            // Direct ID match
            if (in_array($js->id, $userSkillIds)) {
                $isMatched = true;
            } else {
                // Check if any alias of job skill matches any alias of user skill
                $jobTerms = [strtolower($js->name)];
                foreach ($js->aliases as $jAlias) {
                    $jobTerms[] = strtolower($jAlias->alias);
                }

                if (! empty(array_intersect($jobTerms, $userAliasTerms))) {
                    $isMatched = true;
                }
            }

            if ($isMatched) {
                $matchedSkills[] = $js->name;
            } else {
                $missingSkills[] = $js->name;
            }
        }

        $totalReq = $jobSkills->count();
        $matchedCount = count($matchedSkills);
        $score = $totalReq > 0 ? (int) round(($matchedCount / $totalReq) * 100) : 100;

        $explanation = $matchedCount === $totalReq
            ? "Semua keahlian yang disyaratkan ({$matchedCount}/{$totalReq}) Anda kuasai."
            : "Anda menguasai {$matchedCount} dari {$totalReq} keahlian yang dibutuhkan.";

        return [
            'score' => $score,
            'percentage' => $score,
            'weighted_score' => round($score * self::WEIGHT_SKILL, 1),
            'matched_count' => $matchedCount,
            'total_required' => $totalReq,
            'matched_skills' => $matchedSkills,
            'missing_skills' => $missingSkills,
            'explanation' => $explanation,
        ];
    }

    /**
     * 2. Calculate Education Score (Weight 20%)
     * Rule: Hierarchy order (SD=1 to S3=10).
     */
    protected function calculateEducationScore(User $user, Job $job): array
    {
        $profile = $user->profile;
        $userLevel = $profile?->educationLevel;
        $jobLevel = $job->educationLevel;

        if (! $jobLevel) {
            return [
                'score' => 100,
                'percentage' => 100,
                'weighted_score' => round(100 * self::WEIGHT_EDUCATION, 1),
                'user_level' => $userLevel?->name ?? 'Belum diisi',
                'required_level' => 'Semua Jenjang',
                'explanation' => 'Lowongan ini terbuka untuk semua jenjang pendidikan.',
            ];
        }

        if (! $userLevel) {
            return [
                'score' => 50,
                'percentage' => 50,
                'weighted_score' => round(50 * self::WEIGHT_EDUCATION, 1),
                'user_level' => 'Belum diisi',
                'required_level' => $jobLevel->name,
                'explanation' => 'Anda belum mengisi tingkat pendidikan terakhir di profil.',
            ];
        }

        $diff = $userLevel->hierarchy_order - $jobLevel->hierarchy_order;

        if ($diff >= 0) {
            $score = 100;
            $explanation = $diff === 0
                ? "Pendidikan Anda ({$userLevel->name}) persis sesuai persyaratan ({$jobLevel->name})."
                : "Pendidikan Anda ({$userLevel->name}) memenuhi dan melampaui batas minimal ({$jobLevel->name}).";
        } elseif ($diff === -1) {
            $score = 40;
            $explanation = "Pendidikan Anda ({$userLevel->name}) 1 tingkat di bawah syarat ({$jobLevel->name}).";
        } else {
            $score = 0;
            $explanation = "Pendidikan Anda ({$userLevel->name}) belum memenuhi kualifikasi minimal ({$jobLevel->name}).";
        }

        return [
            'score' => $score,
            'percentage' => $score,
            'weighted_score' => round($score * self::WEIGHT_EDUCATION, 1),
            'user_level' => $userLevel->name,
            'required_level' => $jobLevel->name,
            'explanation' => $explanation,
        ];
    }

    /**
     * 3. Calculate Location Score (Weight 20%)
     * Rule: Priority Sumenep, same district, Madura, Jatim, Remote.
     */
    protected function calculateLocationScore(User $user, Job $job): array
    {
        $profile = $user->profile;
        $userDistrict = $profile?->district;
        $userRegency = strtolower($profile?->regency ?? 'sumenep');

        // Remote job
        if ($job->is_remote) {
            return [
                'score' => 100,
                'percentage' => 100,
                'weighted_score' => round(100 * self::WEIGHT_LOCATION, 1),
                'zone' => 'Remote / WFH',
                'explanation' => 'Pekerjaan ini mendukung sistem kerja Remote (dapat dikerjakan dari mana saja).',
            ];
        }

        $jobRegency = strtolower($job->normalized_regency ?? $job->location);
        $jobDistrict = $job->normalized_district;

        $isSumenep = str_contains($jobRegency, 'sumenep') || str_contains(strtolower($job->location), 'sumenep');

        if ($isSumenep) {
            // Check district exact match
            if ($userDistrict && $jobDistrict && strcasecmp(trim($userDistrict), trim($jobDistrict)) === 0) {
                return [
                    'score' => 100,
                    'percentage' => 100,
                    'weighted_score' => round(100 * self::WEIGHT_LOCATION, 1),
                    'zone' => 'Kecamatan Sama',
                    'explanation' => "Lowongan berada di Kecamatan yang sama dengan Anda ({$userDistrict}).",
                ];
            }

            return [
                'score' => 95,
                'percentage' => 95,
                'weighted_score' => round(95 * self::WEIGHT_LOCATION, 1),
                'zone' => 'Kabupaten Sumenep',
                'explanation' => 'Lowongan berlokasi di wilayah Kabupaten Sumenep (Prioritas Utama).',
            ];
        }

        // Madura (Pamekasan, Sampang, Bangkalan)
        $isMadura = str_contains($jobRegency, 'pamekasan') ||
                    str_contains($jobRegency, 'sampang') ||
                    str_contains($jobRegency, 'bangkalan') ||
                    str_contains(strtolower($job->location), 'madura');

        if ($isMadura) {
            $willing = $profile?->willing_outside_sumenep ?? true;
            $score = $willing ? 75 : 35;
            $explanation = $willing
                ? 'Lowongan berada di wilayah Madura dan Anda bersedia bekerja di luar Sumenep.'
                : 'Lowongan berada di Madura (luar Sumenep), sedangkan preferensi Anda fokus di Sumenep.';

            return [
                'score' => $score,
                'percentage' => $score,
                'weighted_score' => round($score * self::WEIGHT_LOCATION, 1),
                'zone' => 'Madura Raya',
                'explanation' => $explanation,
            ];
        }

        // Jawa Timur / Luar Madura
        $willingJatim = $profile?->willing_outside_madura ?? false;
        $score = $willingJatim ? 60 : 20;
        $explanation = $willingJatim
            ? "Lowongan berada di {$job->location} dan Anda bersedia bekerja di luar Madura."
            : "Lowongan berlokasi di luar Madura ({$job->location}).";

        return [
            'score' => $score,
            'percentage' => $score,
            'weighted_score' => round($score * self::WEIGHT_LOCATION, 1),
            'zone' => 'Luar Madura',
            'explanation' => $explanation,
        ];
    }

    /**
     * 4. Calculate Experience Score (Weight 15%)
     * Rule: Compare verified total experience years vs required experience.
     */
    protected function calculateExperienceScore(User $user, Job $job): array
    {
        $requiredYears = $job->min_experience_years;
        $experiences = $user->experiences;

        $userYears = 0.0;
        foreach ($experiences as $exp) {
            $userYears += $exp->duration_years;
        }

        // If job has no experience requirement (0 or unspecified)
        if ($requiredYears <= 0) {
            return [
                'score' => 100,
                'percentage' => 100,
                'weighted_score' => round(100 * self::WEIGHT_EXPERIENCE, 1),
                'user_years' => round($userYears, 1),
                'required_years' => 0,
                'explanation' => 'Lowongan ini tidak mensyaratkan pengalaman kerja (Terbuka untuk Fresh Graduate).',
            ];
        }

        if ($userYears >= $requiredYears) {
            $score = 100;
            $explanation = 'Pengalaman kerja Anda (± '.round($userYears, 1)." tahun) memenuhi syarat minimal ({$requiredYears} tahun).";
        } elseif ($userYears > 0) {
            $ratio = $userYears / $requiredYears;
            $score = (int) round($ratio * 80);
            $score = max(20, min(80, $score));
            $explanation = 'Anda memiliki pengalaman ± '.round($userYears, 1)." tahun dari syarat {$requiredYears} tahun.";
        } else {
            $score = 20;
            $explanation = "Lowongan mensyaratkan minimal {$requiredYears} tahun pengalaman, Anda belum mencantumkan riwayat pengalaman.";
        }

        return [
            'score' => $score,
            'percentage' => $score,
            'weighted_score' => round($score * self::WEIGHT_EXPERIENCE, 1),
            'user_years' => round($userYears, 1),
            'required_years' => $requiredYears,
            'explanation' => $explanation,
        ];
    }

    /**
     * 5. Calculate Interest / Category Score (Weight 10%)
     * Rule: User preferred categories vs Job category.
     */
    protected function calculateInterestScore(User $user, Job $job): array
    {
        $preferredCatIds = $user->preferredCategories()->pluck('job_categories.id')->toArray();

        if (empty($preferredCatIds)) {
            return [
                'score' => 70,
                'percentage' => 70,
                'weighted_score' => round(70 * self::WEIGHT_INTEREST, 1),
                'category' => $job->category?->name ?? 'Umum',
                'explanation' => 'Anda belum memilih kategori minat pekerjaan spesifik (skor netral).',
            ];
        }

        if ($job->job_category_id && in_array($job->job_category_id, $preferredCatIds)) {
            return [
                'score' => 100,
                'percentage' => 100,
                'weighted_score' => round(100 * self::WEIGHT_INTEREST, 1),
                'category' => $job->category?->name,
                'explanation' => "Bidang pekerjaan ({$job->category?->name}) sesuai dengan minat yang Anda pilih di profil.",
            ];
        }

        return [
            'score' => 25,
            'percentage' => 25,
            'weighted_score' => round(25 * self::WEIGHT_INTEREST, 1),
            'category' => $job->category?->name ?? 'Lainnya',
            'explanation' => 'Kategori pekerjaan ini di luar pilihan minat utama Anda.',
        ];
    }

    /**
     * 6. Calculate Age Score (Weight 5%)
     * Rule: User age vs min_age & max_age.
     */
    protected function calculateAgeScore(User $user, Job $job): array
    {
        $birthDate = $user->profile?->birth_date;

        if (! $birthDate) {
            return [
                'score' => 80,
                'percentage' => 80,
                'weighted_score' => round(80 * self::WEIGHT_AGE, 1),
                'user_age' => null,
                'explanation' => 'Tanggal lahir belum diisi di profil (skor usia netral diberikan).',
            ];
        }

        $userAge = Carbon::parse($birthDate)->age;
        $minAge = $job->min_age;
        $maxAge = $job->max_age;

        // No age limit specified in job
        if (! $minAge && ! $maxAge) {
            return [
                'score' => 100,
                'percentage' => 100,
                'weighted_score' => round(100 * self::WEIGHT_AGE, 1),
                'user_age' => $userAge,
                'explanation' => 'Lowongan tidak mencantumkan batasan usia spesifik.',
            ];
        }

        $minMet = ! $minAge || ($userAge >= $minAge);
        $maxMet = ! $maxAge || ($userAge <= $maxAge);

        if ($minMet && $maxMet) {
            $rangeText = ($minAge && $maxAge) ? "{$minAge}–{$maxAge} tahun" : ($minAge ? "min. {$minAge} tahun" : "maks. {$maxAge} tahun");
            $explanation = "Usia Anda ({$userAge} tahun) berada dalam rentang syarat ({$rangeText}).";
            $score = 100;
        } else {
            $rangeText = ($minAge && $maxAge) ? "{$minAge}–{$maxAge} tahun" : ($minAge ? "min. {$minAge} tahun" : "maks. {$maxAge} tahun");
            $explanation = "Usia Anda ({$userAge} tahun) di luar rentang kualifikasi ({$rangeText}).";
            $score = 0;
        }

        return [
            'score' => $score,
            'percentage' => $score,
            'weighted_score' => round($score * self::WEIGHT_AGE, 1),
            'user_age' => $userAge,
            'explanation' => $explanation,
        ];
    }

    /**
     * Compute and store recommendation cache for a user across active jobs.
     */
    public function generateRecommendationsForUser(User $user, int $limit = 50): Collection
    {
        $activeJobs = Job::active()->with(['skills.aliases', 'educationLevel', 'category'])->get();
        $recommendations = collect();

        foreach ($activeJobs as $job) {
            $match = $this->match($user, $job);

            $rec = JobRecommendation::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'job_id' => $job->id,
                ],
                [
                    'total_score' => $match['total_score'],
                    'skill_score' => $match['skill_score'],
                    'education_score' => $match['education_score'],
                    'location_score' => $match['location_score'],
                    'experience_score' => $match['experience_score'],
                    'interest_score' => $match['interest_score'],
                    'age_score' => $match['age_score'],
                    'score_breakdown' => $match['breakdown'],
                    'generated_at' => Carbon::now(),
                ]
            );

            $rec->setRelation('job', $job);
            $recommendations->push($rec);
        }

        return $recommendations->sortByDesc('total_score')->values()->take($limit);
    }
}
