import sys
import argparse
from datetime import datetime
from database import Database
from cleaner import Cleaner
from normalizer import Normalizer
from deduplicator import Deduplicator

from sources.source_disnaker_sumenep import SourceDisnakerSumenep
from sources.source_loker_madura import SourceLokerMadura
from sources.source_portal_jatim import SourcePortalJatim
from sources.source_kitalulus import SourceKitaLulus
from sources.source_glints import SourceGlints
from sources.source_jobstreet import SourceJobStreet
from sources.source_pintarnya import SourcePintarnya

SOURCE_CLASS_MAP = {
    'disnaker': SourceDisnakerSumenep,
    'sumenep': SourceDisnakerSumenep,
    'madura': SourceLokerMadura,
    'loker madura': SourceLokerMadura,
    'jatim': SourcePortalJatim,
    'jawa timur': SourcePortalJatim,
    'kitalulus': SourceKitaLulus,
    'glints': SourceGlints,
    'jobstreet': SourceJobStreet,
    'pintarnya': SourcePintarnya,
}

def resolve_source_class(source_name):
    name_lower = source_name.lower()
    for key, cls in SOURCE_CLASS_MAP.items():
        if key in name_lower:
            return cls
    # Fallback to Disnaker
    return SourceDisnakerSumenep

def run_scraper(target_source="all"):
    print("=" * 60)
    print(" JOS (Job Opportunity Sumenep) — Web Scraper Subsystem")
    print(f" Execution Started at: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print("=" * 60)

    db = Database()
    cleaner = Cleaner()
    normalizer = Normalizer(db)
    deduplicator = Deduplicator(db)

    try:
        sources = db.get_active_sources()
    except Exception as e:
        print(f"[ERROR] Gagal terhubung ke database: {e}")
        sys.exit(1)

    if target_source != "all":
        sources = [s for s in sources if str(s['id']) == str(target_source) or target_source.lower() in s['name'].lower()]

    if not sources:
        print(f"[INFO] Tidak ada sumber scraping aktif untuk filter '{target_source}'.")
        return

    grand_total_found = 0
    grand_total_new = 0
    grand_total_updated = 0
    grand_total_dupes = 0

    for src in sources:
        print(f"\n> Menjalankan scraping untuk: {src['name']}")
        log_id = db.create_log(src['id'])

        source_class = resolve_source_class(src['name'])
        scraper_instance = source_class(src)

        jobs_found = 0
        jobs_new = 0
        jobs_updated = 0
        jobs_duplicates = 0
        error_msg = None

        try:
            raw_jobs = scraper_instance.scrape()
            jobs_found = len(raw_jobs)
            print(f"  Ditemukan {jobs_found} lowongan mentah dari sumber.")

            for raw in raw_jobs:
                # 1. Cleaning
                clean_title = cleaner.clean_whitespace(raw.get('title'))
                clean_company = cleaner.clean_whitespace(raw.get('company'))
                clean_loc = cleaner.clean_whitespace(raw.get('location'))
                clean_desc = cleaner.clean_html(raw.get('description', ''))
                clean_req = cleaner.clean_html(raw.get('requirements', ''))

                s_min, s_max, s_notes = cleaner.parse_salary(raw.get('salary_raw'))
                posted_date = cleaner.parse_date(raw.get('posted_at'))
                deadline_date = cleaner.parse_date(raw.get('deadline'))

                # 2. Hashes for Deduplication
                original_url = raw.get('original_url', '').strip()
                url_hash = deduplicator.generate_url_hash(original_url)
                content_hash = deduplicator.generate_content_hash(clean_title, clean_company, clean_loc)

                # 3. Normalization
                regency, district, is_remote = normalizer.normalize_location(clean_loc)
                cat_id = normalizer.normalize_category(clean_title, clean_desc)
                edu_id = normalizer.normalize_education(raw.get('education_requirement', ''))

                # Parse experience
                exp_req = str(raw.get('experience_requirement', ''))
                min_exp_years = 0
                import re
                exp_match = re.search(r'(\d+)\s*tahun', exp_req.lower())
                if exp_match:
                    min_exp_years = int(exp_match.group(1))

                # Parse age
                age_req = str(raw.get('age_requirement', ''))
                min_age = None
                max_age = None
                age_match = re.findall(r'\d{2}', age_req)
                if len(age_match) >= 2:
                    min_age = int(age_match[0])
                    max_age = int(age_match[1])
                elif len(age_match) == 1:
                    max_age = int(age_match[0])

                # Match skills to master skill IDs
                combined_skills_text = f"{raw.get('skills', '')} {clean_req} {clean_desc}"
                skill_ids = normalizer.match_skills(combined_skills_text)

                job_payload = {
                    'scraping_source_id': src['id'],
                    'original_url': original_url,
                    'url_hash': url_hash,
                    'content_hash': content_hash,
                    'title': clean_title,
                    'normalized_title': clean_title,
                    'company': clean_company,
                    'location': clean_loc,
                    'normalized_regency': regency,
                    'normalized_district': district,
                    'is_remote': is_remote,
                    'job_category_id': cat_id,
                    'employment_type': raw.get('employment_type', 'Full-time'),
                    'education_level_id': edu_id,
                    'min_experience_years': min_exp_years,
                    'min_age': min_age,
                    'max_age': max_age,
                    'salary_min': s_min,
                    'salary_max': s_max,
                    'salary_notes': s_notes,
                    'description': clean_desc,
                    'requirements': clean_req,
                    'raw_skills': str(raw.get('skills', '')),
                    'posted_at': posted_date,
                    'deadline': deadline_date,
                }

                # Save and track action
                action, _ = db.save_job(job_payload, skill_ids)
                if action == 'new':
                    jobs_new += 1
                elif action == 'updated':
                    jobs_updated += 1
                else:
                    jobs_duplicates += 1

            status = 'success'
            db.update_source_last_scraped(src['id'])
            print(f"  [OK] Berhasil: {jobs_new} baru, {jobs_updated} diperbarui, {jobs_duplicates} duplikat.")

        except Exception as e:
            status = 'failed'
            error_msg = str(e)
            print(f"  [FAIL] Gagal scraping sumber {src['name']}: {e}")

        db.update_log(log_id, status, jobs_found, jobs_new, jobs_updated, jobs_duplicates, error_msg)

        grand_total_found += jobs_found
        grand_total_new += jobs_new
        grand_total_updated += jobs_updated
        grand_total_dupes += jobs_duplicates

    db.close()

    print("\n" + "=" * 60)
    print(" RINGKASAN PROSES SCRAPING JOS:")
    print(f" Total Ditemukan: {grand_total_found}")
    print(f" Total Baru: {grand_total_new}")
    print(f" Total Diperbarui: {grand_total_updated}")
    print(f" Selesai pada: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print("=" * 60)

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="JOS Web Scraper Subsystem")
    parser.add_argument("--source", type=str, default="all", help="Source ID or name filter (default: all)")
    args = parser.parse_args()
    run_scraper(args.source)
