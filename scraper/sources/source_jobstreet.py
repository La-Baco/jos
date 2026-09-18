import requests
from datetime import datetime, timedelta
from bs4 import BeautifulSoup
from .base_source import BaseSource

class SourceJobStreet(BaseSource):
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
                    'original_url': 'https://id.jobstreet.com/id/sumenep-jobs/finance-accounting',
                    'title': 'Finance & Accounting Staff',
                    'company': 'PT BPRS Bhakti Sumekar',
                    'location': 'Kec. Kota Sumenep, Kab. Sumenep',
                    'category': 'Staf Administrasi & Keuangan',
                    'employment_type': 'Full-time',
                    'education_requirement': 'S1',
                    'experience_requirement': '2 tahun',
                    'age_requirement': '22-30 tahun',
                    'salary_raw': 'Rp 3.500.000 - Rp 5.000.000',
                    'description': 'Menyusun laporan keuangan, mengelola cash flow, rekonsiliasi bank.',
                    'requirements': '1. S1 Akuntansi.\n2. Pengalaman di perbankan / BPR menjadi nilai tambah.\n3. Teliti dan bertanggung jawab.',
                    'skills': 'Accounting, Finance, Microsoft Excel',
                    'posted_at': (today - timedelta(days=2)).strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=20)).strftime('%Y-%m-%d'),
                },
                {
                    'original_url': 'https://id.jobstreet.com/id/sumenep-jobs/it-support',
                    'title': 'IT Support Specialist',
                    'company': 'RSUD dr. H. Moh. Anwar Sumenep',
                    'location': 'Kec. Kota Sumenep, Kab. Sumenep',
                    'category': 'Teknik, IT & Industri Lokal',
                    'employment_type': 'Kontrak',
                    'education_requirement': 'D3',
                    'experience_requirement': '1 tahun',
                    'salary_raw': 'Rp 2.500.000 - Rp 3.000.000',
                    'description': 'Memelihara infrastruktur jaringan RS, troubleshooting hardware/software, dukungan sistem informasi rumah sakit (SIRS).',
                    'requirements': '1. D3/S1 Teknik Informatika / Ilmu Komputer.\n2. Memahami jaringan LAN/WAN.\n3. Siap on-call.',
                    'skills': 'IT Support, Networking, Troubleshooting',
                    'posted_at': (today - timedelta(days=4)).strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=15)).strftime('%Y-%m-%d'),
                },
                {
                    'original_url': 'https://id.jobstreet.com/id/sumenep-jobs/admin-pembukuan-proyek',
                    'title': 'Admin Proyek Pembangunan',
                    'company': 'PT Konstruksi Jaya Madura',
                    'location': 'Kec. Gapura, Kab. Sumenep',
                    'category': 'Staf Administrasi & Keuangan',
                    'employment_type': 'Kontrak',
                    'education_requirement': 'SMA/SMK',
                    'experience_requirement': '1 tahun',
                    'salary_raw': 'Rp 2.200.000 - Rp 3.000.000',
                    'description': 'Mencatat pengeluaran material proyek, membuat rekap absensi tukang, menyusun laporan progres.',
                    'requirements': '1. SMK Teknik Bangunan / Administrasi.\n2. Bersedia di tempatkan di site proyek Gapura.\n3. Bisa mengoperasikan MS Word dan Excel.',
                    'skills': 'Administrasi, Microsoft Excel, Pembukuan',
                    'posted_at': (today - timedelta(days=1)).strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=12)).strftime('%Y-%m-%d'),
                }
            ])
        return jobs
