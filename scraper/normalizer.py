import re

class Normalizer:
    SUMENEP_DISTRICTS = [
        'Kota Sumenep', 'Kalianget', 'Talango', 'Saronggi', 'Bluto',
        'Pragaan', 'Lenteng', 'Ganding', 'Guluk-Guluk', 'Rubaru',
        'Ambunten', 'Pasongsongan', 'Dasuk', 'Manding', 'Batuputih',
        'Dungkek', 'Gapura', 'Batang-Batang', 'Giligenting', 'Gayam',
        'Nonggunong', 'Raas', 'Arjasa', 'Kangayan', 'Sapeken', 'Masalembu'
    ]

    def __init__(self, db):
        self.db = db
        self.categories = db.get_categories()
        self.education_levels = db.get_education_levels()
        self.master_skills = db.get_master_skills_with_aliases()

    def normalize_location(self, raw_location):
        if not raw_location:
            return None, None, False

        loc_lower = raw_location.lower()

        is_remote = bool(re.search(r'\b(remote|wfh|work from home|daring)\b', loc_lower))
        if is_remote:
            return 'Remote', None, True

        regency = None
        district = None

        if 'sumenep' in loc_lower:
            regency = 'Sumenep'
            # Look for specific district
            for d in self.SUMENEP_DISTRICTS:
                if d.lower() in loc_lower:
                    district = d
                    break
        elif 'pamekasan' in loc_lower:
            regency = 'Pamekasan'
        elif 'sampang' in loc_lower:
            regency = 'Sampang'
        elif 'bangkalan' in loc_lower:
            regency = 'Bangkalan'
        elif 'surabaya' in loc_lower:
            regency = 'Surabaya'
        elif 'sidoarjo' in loc_lower:
            regency = 'Sidoarjo'
        elif 'malang' in loc_lower:
            regency = 'Malang'
        else:
            regency = raw_location.split(',')[0].strip()

        return regency, district, is_remote

    def normalize_education(self, text):
        if not text:
            return None

        t = text.lower()
        if re.search(r'\b(s3|doktor)\b', t):
            code = 'S3'
        elif re.search(r'\b(s2|magister)\b', t):
            code = 'S2'
        elif re.search(r'\b(s1|sarjana|d4)\b', t):
            code = 'S1'
        elif re.search(r'\b(d3|diploma 3|ahli madya)\b', t):
            code = 'D3'
        elif re.search(r'\b(d1|d2|diploma)\b', t):
            code = 'D1'
        elif re.search(r'\b(sma|smk|ma|slta|sederajat)\b', t):
            code = 'SMA/SMK'
        elif re.search(r'\b(smp|mts|sltp)\b', t):
            code = 'SMP'
        elif re.search(r'\b(sd|mi)\b', t):
            code = 'SD'
        else:
            return None

        for edu in self.education_levels:
            if edu['code'] == code:
                return edu['id']

        return None

    def normalize_category(self, title, description=""):
        combined = f"{title} {description}".lower()

        for cat in self.categories:
            cat_name = cat['name'].lower()
            slug = cat['slug']

            if 'admin' in slug and any(w in combined for w in ['admin', 'tata usaha', 'data entry', 'resepsionis', 'sekretaris']):
                return cat['id']
            if 'it-' in slug and any(w in combined for w in ['programmer', 'developer', 'it support', 'komputer', 'software', 'web', 'jaringan']):
                return cat['id']
            if 'marketing' in slug and any(w in combined for w in ['sales', 'marketing', 'pemasaran', 'canvasser', 'promotor']):
                return cat['id']
            if 'keuangan' in slug and any(w in combined for w in ['akuntansi', 'accounting', 'kasir', 'pembukuan', 'finance']):
                return cat['id']
            if 'logistik' in slug and any(w in combined for w in ['gudang', 'logistik', 'packer', 'kurir', 'warehouse']):
                return cat['id']
            if 'pelayanan' in slug and any(w in combined for w in ['customer service', 'cs', 'call center', 'front desk']):
                return cat['id']
            if 'kesehatan' in slug and any(w in combined for w in ['perawat', 'apoteker', 'bidan', 'klinik', 'medis']):
                return cat['id']
            if 'pendidikan' in slug and any(w in combined for w in ['guru', 'pengajar', 'tutor', 'bimbel', 'dosen']):
                return cat['id']

        return None

    def match_skills(self, text_or_tags):
        if not text_or_tags:
            return []

        search_text = str(text_or_tags).lower()
        matched_ids = set()

        for s in self.master_skills:
            skill_id = s['id']
            skill_name = s['name'].lower()
            alias = s.get('alias')

            # Match skill name as word or phrase
            if re.search(r'\b' + re.escape(skill_name) + r'\b', search_text):
                matched_ids.add(skill_id)
                continue

            # Match alias
            if alias:
                alias_lower = alias.lower()
                if re.search(r'\b' + re.escape(alias_lower) + r'\b', search_text):
                    matched_ids.add(skill_id)

        return list(matched_ids)
