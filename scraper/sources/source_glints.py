import json
import requests
from datetime import datetime, timedelta
from bs4 import BeautifulSoup
from .base_source import BaseSource

class SourceGlints(BaseSource):
    def scrape(self):
        jobs = []
        target_url = 'https://glints.com/id/opportunities/jobs/explore?keyword=Sumenep'

        try:
            headers = self.get_headers()
            headers['Referer'] = 'https://glints.com/'
            response = requests.get(target_url, headers=headers, timeout=12)

            if response.status_code == 200:
                soup = BeautifulSoup(response.text, 'html.parser')
                script = soup.find('script', id='__NEXT_DATA__')
                if script and script.string:
                    data = json.loads(script.string)
                    jobs_in_page = data.get('props', {}).get('pageProps', {}).get('initialJobs', {}).get('jobsInPage', [])

                    for item in jobs_in_page:
                        title = item.get('title')
                        if not title:
                            continue

                        job_id = item.get('id')
                        company_info = item.get('company') or {}
                        company_name = company_info.get('name') or 'Perusahaan Mitra Glints'
                        
                        # Skills extraction
                        skills_list = []
                        for sk in (item.get('skills') or []):
                            sk_detail = sk.get('skill') or {}
                            if sk_detail and sk_detail.get('name'):
                                skills_list.append(sk_detail.get('name'))

                        # Salary extraction
                        salaries = item.get('salaries') or []
                        salary_raw = None
                        if salaries:
                            s = salaries[0]
                            min_s = s.get('minSalary')
                            max_s = s.get('maxSalary')
                            curr = s.get('currency') or 'IDR'
                            if min_s and max_s:
                                salary_raw = f"{curr} {min_s:,.0f} - {max_s:,.0f}"
                            elif min_s:
                                salary_raw = f"{curr} {min_s:,.0f}"

                        # Education mapping
                        edu_raw = item.get('educationLevel', '')
                        if 'HIGH_SCHOOL' in edu_raw:
                            edu_str = 'SMA/SMK'
                        elif 'BACHELOR' in edu_raw:
                            edu_str = 'S1'
                        elif 'DIPLOMA' in edu_raw:
                            edu_str = 'D3'
                        else:
                            edu_str = 'Semua Jenjang'

                        # Experience
                        min_exp = item.get('minYearsOfExperience', 0) or 0
                        exp_str = f"{min_exp} tahun" if min_exp > 0 else 'Fresh Graduate'

                        # Employment type
                        emp_type_raw = item.get('type', 'FULL_TIME')
                        emp_type = 'Kontrak' if 'CONTRACT' in emp_type_raw else ('Magang' if 'INTERN' in emp_type_raw else 'Full-time')

                        # Location & City
                        city_info = item.get('city') or {}
                        loc_name = city_info.get('name') or 'Sumenep, Jawa Timur'
                        if 'sumenep' not in loc_name.lower():
                            loc_name = f"Sumenep ({loc_name})"

                        # Dates
                        created_at = item.get('createdAt')
                        posted_at = created_at[:10] if created_at else datetime.now().strftime('%Y-%m-%d')
                        deadline = (datetime.now() + timedelta(days=30)).strftime('%Y-%m-%d')

                        jobs.append({
                            'original_url': f"https://glints.com/id/opportunities/jobs/{job_id}",
                            'title': title,
                            'company': company_name,
                            'location': loc_name,
                            'category': 'Layanan Pelanggan & Sales',
                            'employment_type': emp_type,
                            'education_requirement': edu_str,
                            'experience_requirement': exp_str,
                            'salary_raw': salary_raw,
                            'description': f"Lowongan {title} di {company_name}. Penempatan area Sumenep dan sekitarnya melalui Glints.",
                            'requirements': f"Kualifikasi: Pendidikan {edu_str}, pengalaman {exp_str}.",
                            'skills': ', '.join(skills_list) if skills_list else 'Komunikasi, Kerja Sama Tim',
                            'posted_at': posted_at,
                            'deadline': deadline,
                        })

        except Exception as e:
            print(f"  [WARN] Glints live scraper error: {e}")

        if not jobs:
            jobs = self.get_fallback_jobs()

        return jobs

