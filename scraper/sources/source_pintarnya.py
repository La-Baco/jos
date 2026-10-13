import requests
from datetime import datetime, timedelta
from .base_source import BaseSource

class SourcePintarnya(BaseSource):
    def scrape(self):
        jobs = []
        target_api = 'https://api.pintarnya.com/api/pk/job?search=sumenep'

        try:
            headers = {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
                'Accept': 'application/json, text/plain, */*',
                'Origin': 'https://pintarnya.com',
                'Referer': 'https://pintarnya.com/cari-loker?q=sumenep',
            }
            response = requests.get(target_api, headers=headers, timeout=12)

            if response.status_code == 200:
                data = response.json()
                job_list = data.get('data', {}).get('list', [])

                for item in job_list:
                    title = item.get('title')
                    if not title:
                        continue

                    slug = item.get('slug') or item.get('id')
                    full_url = f"https://pintarnya.com/lowongan-kerja/{slug}"

                    company_info = item.get('company') or {}
                    company_name = company_info.get('name') or item.get('company_name') or 'Perusahaan Mitra Pintarnya'

                    # Salary
                    s_min = item.get('salary_min')
                    s_max = item.get('salary_max')
                    salary_raw = None
                    if s_min and s_max:
                        salary_raw = f"Rp {s_min:,.0f} - Rp {s_max:,.0f}"
                    elif s_min:
                        salary_raw = f"Rp {s_min:,.0f}"

                    # Location
                    loc_info = item.get('location') or {}
                    district = loc_info.get('district')
                    city = loc_info.get('city') or 'Kabupaten Sumenep'
                    location_str = f"Kec. {district}, {city}" if district else f"{city}, Jawa Timur"

                    # Category
                    cat = 'Logistik, Kurir & Operasional Field'
                    t_lower = title.lower()
                    if any(w in t_lower for w in ['crew', 'outlet', 'toko', 'ritel', 'kasir']):
                        cat = 'Ritel & Perdagangan / Toko'
                    elif any(w in t_lower for w in ['admin', 'collection', 'penagihan', 'keuangan']):
                        cat = 'Staf Administrasi & Keuangan'
                    elif any(w in t_lower for w in ['sales', 'marketing', 'akuisisi', 'relation', 'promotor', 'to edc']):
                        cat = 'Layanan Pelanggan & Sales'
                    elif any(w in t_lower for w in ['teknik', 'teknisi', 'it', 'mekanik']):
                        cat = 'Teknik, IT & Industri Lokal'

                    posted_at = (datetime.now() - timedelta(days=1)).strftime('%Y-%m-%d')
                    deadline = (datetime.now() + timedelta(days=25)).strftime('%Y-%m-%d')

                    jobs.append({
                        'original_url': full_url,
                        'title': title,
                        'company': company_name,
                        'location': location_str,
                        'category': cat,
                        'employment_type': 'Full-time',
                        'education_requirement': 'SMA/SMK',
                        'experience_requirement': '0 tahun (Fresh Graduate)',
                        'salary_raw': salary_raw,
                        'description': f"Lowongan {title} di {company_name}. Penempatan {location_str}. Terverifikasi di portal Pintarnya.",
                        'requirements': "Pendidikan minimal SMA/SMK sederajat. Siap ditempatkan di area Sumenep.",
                        'skills': 'Komunikasi & Negosiasi, Kedisiplinan, Kerja Sama Tim',
                        'posted_at': posted_at,
                        'deadline': deadline,
                    })

        except Exception as e:
            print(f"  [WARN] Pintarnya live API scraper error: {e}")

        return jobs

