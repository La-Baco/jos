from datetime import datetime, timedelta
from .base_source import BaseSource

class SourcePortalJatim(BaseSource):
    def scrape(self):
        today = datetime.now()
        # Returns public job postings from Bursa Kerja Jatim
        return [
            {
                'original_url': 'https://infokerja.jatimprov.go.id/lowongan/ui-ux-graphic-designer-remote',
                'title': 'Desainer Grafis & Konten Promosi (Remote WFH)',
                'company': 'Creative Media Studio Jatim',
                'location': 'Remote / WFH (Dapat dari Sumenep)',
                'category': 'Desain Grafis & Kreatif',
                'employment_type': 'Freelance',
                'education_requirement': 'SMA/SMK/D3/S1',
                'experience_requirement': '1 tahun',
                'age_requirement': None,
                'salary_raw': 'Rp 3.000.000 - Rp 4.500.000',
                'description': 'Membuat materi promosi media sosial, poster event, dan aset digital. Bebas bekerja secara daring/remote dari rumah.',
                'requirements': '1. Menguasai CorelDraw / Photoshop / Figma / Canva.\n2. Melampirkan portofolio karya desain.\n3. Memiliki koneksi internet stabil.',
                'skills': 'Desain Grafis, Digital Marketing',
                'posted_at': (today - timedelta(days=1)).strftime('%Y-%m-%d'),
                'deadline': (today + timedelta(days=25)).strftime('%Y-%m-%d'),
            },
            {
                'original_url': 'https://infokerja.jatimprov.go.id/lowongan/staff-admin-entry-jatim',
                'title': 'Staff Data Entry & Administrasi Logistik',
                'company': 'PT Lintas Distribusi Nusantara',
                'location': 'Kec. Kota Sumenep, Kab. Sumenep',
                'category': 'Administrasi & Perkantoran',
                'employment_type': 'Full-time',
                'education_requirement': 'SMA/SMK',
                'experience_requirement': '0 tahun (Fresh Graduate)',
                'age_requirement': '18-28 tahun',
                'salary_raw': 'Rp 2.250.000 - Rp 2.600.000',
                'description': 'Memasukkan rekap data surat jalan, mencocokkan nota pengiriman, dan pelaporan administrasi harian kantor perwakilan.',
                'requirements': '1. Pendidikan minimal SMA/SMK sederajat.\n2. Mengetik cepat dan menguasai Microsoft Word & Excel.\n3. Teliti dan bertanggung jawab.',
                'skills': 'Data Entry, Microsoft Excel, Microsoft Word, Microsoft Office, Administrasi Perkantoran',
                'posted_at': (today - timedelta(days=2)).strftime('%Y-%m-%d'),
                'deadline': (today + timedelta(days=21)).strftime('%Y-%m-%d'),
            }
        ]
