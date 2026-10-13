import re

class Normalizer:
    # 27 Kecamatan resmi di Kabupaten Sumenep (18 Daratan + 9 Kepulauan)
    SUMENEP_DISTRICTS = [
        'Kota Sumenep', 'Batuan', 'Kalianget', 'Talango', 'Saronggi', 'Bluto',
        'Pragaan', 'Lenteng', 'Ganding', 'Guluk-Guluk', 'Rubaru',
        'Ambunten', 'Pasongsongan', 'Dasuk', 'Manding', 'Batuputih',
        'Dungkek', 'Gapura', 'Batang-Batang', 'Giligenting', 'Gayam',
        'Nonggunong', 'Raas', 'Arjasa', 'Kangayan', 'Sapeken', 'Masalembu'
    ]

    DISTRICT_ALIASES = {
        'sumenep kota': 'Kota Sumenep',
        'kota': 'Kota Sumenep',
        'kangean': 'Arjasa',
        'pulau kangean': 'Arjasa',
        'pulau sapeken': 'Sapeken',
        'pulau raas': 'Raas',
        'p. raas': 'Raas',
        'pulau masalembu': 'Masalembu',
        'guluk guluk': 'Guluk-Guluk',
        'batang batang': 'Batang-Batang',
        'marengan': 'Kalianget',
        'kolor': 'Kota Sumenep',
        'pabian': 'Kota Sumenep',
        'pamolokan': 'Kota Sumenep',
        'kepanjin': 'Kota Sumenep',
    }

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

        if 'sumenep' in loc_lower or any(d.lower() in loc_lower for d in self.SUMENEP_DISTRICTS) or any(alias in loc_lower for alias in self.DISTRICT_ALIASES):
            regency = 'Sumenep'

            # 1. Check direct aliases first
            for alias, target_district in self.DISTRICT_ALIASES.items():
                if re.search(r'\b' + re.escape(alias) + r'\b', loc_lower):
                    district = target_district
                    break

            # 2. Check 27 kecamatan names
            if not district:
                for d in self.SUMENEP_DISTRICTS:
                    if re.search(r'\b' + re.escape(d.lower()) + r'\b', loc_lower):
                        district = d
                        break

            # 3. Default to Kota Sumenep if general Sumenep without specific district
            if not district and 'sumenep' in loc_lower:
                district = 'Kota Sumenep'

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

    def normalize_category(self, title, description="", raw_category=None):
        combined = f"{title} {description} {raw_category or ''}".lower()

        for cat in self.categories:
            slug = cat.get('slug', '').lower()

            if 'ritel' in slug and any(w in combined for w in [
                'ritel', 'retail', 'toko', 'pramuniaga', 'kasir', 'store',
                'minimarket', 'supermarket', 'fashion', 'penjaga stan', 'sembako', 'crew store'
            ]):
                return cat['id']

            if 'logistik' in slug and any(w in combined for w in [
                'gudang', 'logistik', 'packer', 'kurir', 'warehouse',
                'driver', 'supir', 'pengemudi', 'ekspedisi', 'spx', 'j&t', 'jne', 'distribusi', 'helper'
            ]):
                return cat['id']

            if 'admin' in slug and any(w in combined for w in [
                'admin', 'tata usaha', 'data entry', 'resepsionis', 'sekretaris',
                'akuntansi', 'accounting', 'pembukuan', 'finance', 'perpajakan', 'keuangan', 'staf kantor'
            ]):
                return cat['id']

            if 'layanan' in slug and any(w in combined for w in [
                'sales', 'marketing', 'pemasaran', 'canvasser', 'promotor',
                'customer service', 'cs', 'pelayanan', 'front office', 'spg', 'spb', 'telemarketing', 'penjualan'
            ]):
                return cat['id']

            if 'teknik' in slug and any(w in combined for w in [
                'programmer', 'developer', 'it support', 'komputer', 'software',
                'web', 'jaringan', 'teknisi', 'mekanik', 'listrik', 'mesin',
                'ac', 'operator produksi', 'pabrik', 'desain', 'grafis'
            ]):
                return cat['id']

        # Fallback to general admin/default category if none matched
        for cat in self.categories:
            if 'admin' in cat.get('slug', ''):
                return cat['id']

        return self.categories[0]['id'] if self.categories else None

    def normalize_employment_type(self, raw_type, text=""):
        combined = f"{raw_type or ''} {text}".lower()
        if re.search(r'\b(magang|intern|internship)\b', combined):
            return 'Magang'
        if re.search(r'\b(part-time|part time|paruh waktu)\b', combined):
            return 'Part-time'
        if re.search(r'\b(freelance|lepas|proyek)\b', combined):
            return 'Freelance'
        if re.search(r'\b(kontrak|pkwt|contract|outsourcing)\b', combined):
            return 'Kontrak'
        return 'Full-time'

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
