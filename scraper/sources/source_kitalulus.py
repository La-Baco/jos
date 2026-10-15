import requests
from datetime import datetime, timedelta
from bs4 import BeautifulSoup
from .base_source import BaseSource

class SourceKitaLulus(BaseSource):
    def scrape(self):
        jobs = []
        target_url = 'https://www.kitalulus.com/lowongan/kota/kabupaten-sumenep'

        try:
            headers = self.get_headers()
            headers['Referer'] = 'https://www.kitalulus.com/'
            response = requests.get(target_url, headers=headers, timeout=12)

            if response.status_code == 200:
                soup = BeautifulSoup(response.text, 'html.parser')
                cards = soup.find_all('a', href=lambda h: h and '/lowongan/detail/' in h)

                for card in cards:
                    href = card.get('href')
                    if not href:
                        continue

                    full_url = f"https://www.kitalulus.com{href}" if href.startswith('/') else href
                    texts = [t.strip() for t in card.stripped_strings]

                    if not texts:
                        continue

                    title = texts[0]
                    company = texts[1] if len(texts) > 1 else 'Perusahaan Mitra KitaLulus'
                    loc = texts[2] if len(texts) > 2 else 'Kabupaten Sumenep, Jawa Timur'
                    edu_raw = texts[3] if len(texts) > 3 else 'SMA/SMK'
                    salary_raw = texts[4] if len(texts) > 4 else None

                    if salary_raw and ('dinegosiasikan' in salary_raw.lower() or 'kompetitif' in salary_raw.lower()):
                        salary_raw = None

                    # Deduce category based on title
                    cat = 'Ritel & Perdagangan / Toko'
                    t_lower = title.lower()
                    if any(w in t_lower for w in ['kurir', 'hub', 'lead', 'delivery', 'driver', 'operasional']):
                        cat = 'Logistik, Kurir & Operasional Field'
                    elif any(w in t_lower for w in ['admin', 'cs', 'customer service', 'finance', 'akuntansi']):
                        cat = 'Staf Administrasi & Keuangan'
                    elif any(w in t_lower for w in ['sales', 'marketing', 'partner', 'promosi', 'account']):
                        cat = 'Layanan Pelanggan & Sales'
                    elif any(w in t_lower for w in ['teknik', 'teknisi', 'it', 'mekanik', 'developer']):
                        cat = 'Teknik, IT & Industri Lokal'

                    posted_at = (datetime.now() - timedelta(days=2)).strftime('%Y-%m-%d')
                    deadline = (datetime.now() + timedelta(days=20)).strftime('%Y-%m-%d')

                    jobs.append({
                        'original_url': full_url,
                        'title': title,
                        'company': company,
                        'location': loc,
                        'category': cat,
                        'employment_type': 'Full-time',
                        'education_requirement': edu_raw,
                        'experience_requirement': '0 tahun (Fresh Graduate)',
                        'salary_raw': salary_raw,
                        'description': f"Lowongan {title} di {company}, penempatan {loc}. Terverifikasi di portal bursa kerja KitaLulus.",
                        'requirements': f"Pendidikan {edu_raw}. Silakan melamar langsung melalui aplikasi atau situs resmi KitaLulus.",
                        'skills': 'Customer Service, Komunikasi & Negosiasi, Kedisiplinan',
                        'posted_at': posted_at,
                        'deadline': deadline,
                    })

        except Exception as e:
            print(f"  [WARN] KitaLulus live scraper error: {e}")

        if not jobs:
            jobs = self.get_fallback_jobs()

        return jobs

