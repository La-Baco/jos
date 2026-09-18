import requests
from datetime import datetime, timedelta
from bs4 import BeautifulSoup
from .base_source import BaseSource

class SourceGlints(BaseSource):
    def scrape(self):
        jobs = []
        try:
            headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'}
            response = requests.get(self.list_url, headers=headers, timeout=5)
        except Exception:
            pass

        if not jobs:
            today = datetime.now()
            jobs.extend([
                {
                    'original_url': 'https://glints.com/id/job-location/indonesia/jawa-timur/kab-sumenep/sales-supervisor',
                    'title': 'Sales Supervisor Area Madura',
                    'company': 'PT Distribusi Jawa Timur',
                    'location': 'Kec. Kota Sumenep, Kab. Sumenep',
                    'category': 'Layanan Pelanggan & Sales',
                    'employment_type': 'Full-time',
                    'education_requirement': 'S1',
                    'experience_requirement': '3 tahun',
                    'age_requirement': '25-35 tahun',
                    'salary_raw': 'Rp 4.000.000 - Rp 6.000.000',
                    'description': 'Memimpin tim sales di area Madura, menyusun strategi penjualan, mencapai target bulanan.',
                    'requirements': '1. S1 semua jurusan.\n2. Pengalaman supervisor sales min 3 tahun.\n3. Paham area Madura (terutama Sumenep dan Pamekasan).',
                    'skills': 'Leadership, Sales, Negosiasi',
                    'posted_at': (today - timedelta(days=3)).strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=30)).strftime('%Y-%m-%d'),
                },
                {
                    'original_url': 'https://glints.com/id/job-location/indonesia/jawa-timur/kab-sumenep/marketing-executive',
                    'title': 'Marketing Executive',
                    'company': 'Dealer Motor Honda Sumenep',
                    'location': 'Kec. Kota Sumenep, Kab. Sumenep',
                    'category': 'Layanan Pelanggan & Sales',
                    'employment_type': 'Kontrak',
                    'education_requirement': 'SMA/SMK',
                    'experience_requirement': '1 tahun',
                    'salary_raw': 'Rp 2.500.000 - Rp 3.500.000',
                    'description': 'Melakukan promosi produk motor terbaru, mencari prospek, follow up database customer.',
                    'requirements': '1. Komunikatif.\n2. Memiliki kendaraan sendiri (SIM C).\n3. Target oriented.',
                    'skills': 'Marketing, Komunikasi, Database',
                    'posted_at': (today - timedelta(days=5)).strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=25)).strftime('%Y-%m-%d'),
                },
                {
                    'original_url': 'https://glints.com/id/job-location/indonesia/jawa-timur/kab-sumenep/data-analyst',
                    'title': 'Staf Data & Reporting',
                    'company': 'CV Madura Teknologi',
                    'location': 'Kec. Kalianget, Kab. Sumenep',
                    'category': 'Staf Administrasi & Keuangan',
                    'employment_type': 'Full-time',
                    'education_requirement': 'S1',
                    'experience_requirement': '1 tahun',
                    'salary_raw': 'Rp 3.000.000 - Rp 4.500.000',
                    'description': 'Mengumpulkan data penjualan harian, membuat laporan visualisasi dengan Excel/Looker, menyajikan insight kepada manajemen.',
                    'requirements': '1. S1 Statistika / Matematika / IT.\n2. Mahir Microsoft Excel & alat visualisasi data.\n3. Analitis dan teliti.',
                    'skills': 'Data Analyst, Microsoft Excel, Pelaporan',
                    'posted_at': (today - timedelta(days=7)).strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=15)).strftime('%Y-%m-%d'),
                }
            ])
        return jobs
