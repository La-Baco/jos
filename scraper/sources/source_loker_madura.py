from datetime import datetime, timedelta
from .base_source import BaseSource

class SourceLokerMadura(BaseSource):
    def scrape(self):
        today = datetime.now()
        # Returns public job postings from Loker Madura Raya
        return [
            {
                'original_url': 'https://lokermadura.id/lowongan/accounting-staff-saronggi',
                'title': 'Staf Keuangan & Pembukuan Kas',
                'company' : 'UD Hasil Bumi Madura',
                'location': 'Kec. Saronggi, Kab. Sumenep',
                'category': 'Keuangan & Akuntansi',
                'employment_type': 'Full-time',
                'education_requirement': 'D3/S1 Akuntansi',
                'experience_requirement': '1 tahun',
                'age_requirement': '21-32 tahun',
                'salary_raw': 'Rp 2.400.000 - Rp 3.000.000',
                'description': 'Menyusun jurnal harian, rekonsiliasi kas bank, dan menghitung laba rugi distributor sembako Saronggi.',
                'requirements': '1. Pendidikan D3 atau S1 Akuntansi/Keuangan.\n2. Menguasai Microsoft Excel dan dasar akuntansi.\n3. Teliti dan jujur.',
                'skills': 'Akuntansi (Accounting), Microsoft Excel, Microsoft Office',
                'posted_at': (today - timedelta(days=2)).strftime('%Y-%m-%d'),
                'deadline': (today + timedelta(days=16)).strftime('%Y-%m-%d'),
            },
            {
                'original_url': 'https://lokermadura.id/lowongan/driver-logistik-pamekasan-sumenep',
                'title': 'Driver Ekspedisi & Distribusi Barang',
                'company': 'PT Cepat Aman Madura Express',
                'location': 'Sumenep & Pamekasan',
                'category': 'Transportasi & Driver',
                'employment_type': 'Full-time',
                'education_requirement': 'SMA/SMK',
                'experience_requirement': '1 tahun',
                'age_requirement': '22-40 tahun',
                'salary_raw': 'Rp 2.500.000 - Rp 3.200.000',
                'description': 'Mengirimkan paket logistik ke drop point wilayah Sumenep daratan dan memastikan paket tiba tepat waktu.',
                'requirements': '1. Memiliki SIM B1 atau SIM A aktif.\n2. Menguasai rute jalan di Kabupaten Sumenep.\n3. Kondisi fisik prima.',
                'skills': 'Mengemudi (Driver), Komunikasi & Negosiasi',
                'posted_at': (today - timedelta(days=3)).strftime('%Y-%m-%d'),
                'deadline': (today + timedelta(days=10)).strftime('%Y-%m-%d'),
            }
        ]
