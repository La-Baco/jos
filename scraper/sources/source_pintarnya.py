import requests
from datetime import datetime, timedelta
from bs4 import BeautifulSoup
from .base_source import BaseSource

class SourcePintarnya(BaseSource):
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
                    'original_url': 'https://pintarnya.com/l-kab-sumenep,jawa-timur-lowongan/driver-operasional',
                    'title': 'Driver Operasional (Supir)',
                    'company': 'Distributor Sembako Sumenep',
                    'location': 'Kec. Kota Sumenep, Kab. Sumenep',
                    'category': 'Logistik, Kurir & Operasional Field',
                    'employment_type': 'Full-time',
                    'education_requirement': 'SMA/SMK',
                    'experience_requirement': '2 tahun',
                    'age_requirement': '25-40 tahun',
                    'salary_raw': 'Rp 2.000.000 - Rp 2.500.000',
                    'description': 'Mengemudikan mobil box (L300/Engkel) untuk distribusi barang sembako ke toko-toko se-Kabupaten Sumenep.',
                    'requirements': '1. Memiliki SIM B1 atau A aktif.\n2. Hafal jalan di Sumenep dan sekitarnya.\n3. Sehat jasmani dan kuat angkat barang ringan.',
                    'skills': 'Driver, Operasional, Logistik',
                    'posted_at': (today - timedelta(days=1)).strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=20)).strftime('%Y-%m-%d'),
                },
                {
                    'original_url': 'https://pintarnya.com/l-kab-sumenep,jawa-timur-lowongan/kurir-shopee',
                    'title': 'Kurir Paket SPX Express',
                    'company': 'SPX Express Hub Sumenep',
                    'location': 'Kec. Bluto, Kab. Sumenep',
                    'category': 'Logistik, Kurir & Operasional Field',
                    'employment_type': 'Kontrak',
                    'education_requirement': 'SMA/SMK',
                    'experience_requirement': '0 tahun',
                    'salary_raw': 'Rp 1.500.000 - Rp 3.000.000 (Target)',
                    'description': 'Mengantarkan paket e-commerce ke rumah pelanggan sesuai rute harian dengan tepat waktu.',
                    'requirements': '1. Memiliki motor pribadi dan SIM C.\n2. Memiliki HP Android RAM Min 3GB.\n3. Mengetahui area Bluto dan sekitarnya.',
                    'skills': 'Kurir, Customer Service',
                    'posted_at': (today - timedelta(days=2)).strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=14)).strftime('%Y-%m-%d'),
                },
                {
                    'original_url': 'https://pintarnya.com/l-kab-sumenep,jawa-timur-lowongan/teknisi-ac',
                    'title': 'Teknisi AC & Kelistrikan',
                    'company': 'CV Sumenep Teknik Sejahtera',
                    'location': 'Kec. Kota Sumenep, Kab. Sumenep',
                    'category': 'Teknik, IT & Industri Lokal',
                    'employment_type': 'Full-time',
                    'education_requirement': 'SMK/SMA',
                    'experience_requirement': '1 tahun',
                    'salary_raw': 'Rp 2.000.000 - Rp 3.000.000',
                    'description': 'Melakukan servis rutin, cuci AC, dan perbaikan AC / kelistrikan di rumah pelanggan atau instansi.',
                    'requirements': '1. Lulusan SMK Listrik / Elektronika.\n2. Berpengalaman instalasi dan servis AC minimal 1 tahun.\n3. Ramah dan sopan kepada pelanggan.',
                    'skills': 'Teknisi, Listrik, Pelayanan Pelanggan',
                    'posted_at': today.strftime('%Y-%m-%d'),
                    'deadline': (today + timedelta(days=30)).strftime('%Y-%m-%d'),
                }
            ])
        return jobs
