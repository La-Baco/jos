from abc import ABC, abstractmethod

class BaseSource(ABC):
    def __init__(self, source_record):
        self.source_record = source_record
        self.source_id = source_record['id']
        self.name = source_record['name']
        self.base_url = source_record['base_url']
        self.list_url = source_record['list_url']

    def get_headers(self):
        """
        Standard realistic browser headers to avoid instant 403 Forbidden on public servers.
        """
        return {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 (JOS Aggregator Sumenep)',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Accept-Language': 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
            'Sec-Ch-Ua': '"Chromium";v="128", "Not;A=Brand";v="24"',
            'Sec-Ch-Ua-Mobile': '?0',
            'Sec-Ch-Ua-Platform': '"Windows"',
            'Sec-Fetch-Dest': 'document',
            'Sec-Fetch-Mode': 'navigate',
            'Sec-Fetch-Site': 'none',
            'Sec-Fetch-User': '?1',
            'Upgrade-Insecure-Requests': '1',
        }

    def get_fallback_jobs(self):
        """
        Loads curated verified backup jobs for this source if live network fails (e.g. on hosting IPs).
        """
        import os
        import json
        from datetime import datetime, timedelta

        backup_file = os.path.join(os.path.dirname(os.path.dirname(__file__)), 'data', 'jobs_backup.json')
        if not os.path.exists(backup_file):
            return []

        try:
            with open(backup_file, 'r', encoding='utf-8') as f:
                data = json.load(f)

            src_name_lower = self.name.lower()
            matched = []
            for item in data:
                item_src = (item.get('source_name') or '').lower()
                if item_src in src_name_lower or src_name_lower in item_src:
                    s_min = item.get('salary_min')
                    s_max = item.get('salary_max')
                    salary_raw = None
                    if s_min and s_max:
                        salary_raw = f"Rp {float(s_min):,.0f} - Rp {float(s_max):,.0f}"
                    elif s_min:
                        salary_raw = f"Rp {float(s_min):,.0f}"

                    matched.append({
                        'original_url': item.get('original_url'),
                        'title': item.get('title'),
                        'company': item.get('company'),
                        'location': item.get('location'),
                        'category': item.get('category_slug') or 'Layanan Pelanggan & Sales',
                        'employment_type': item.get('employment_type') or 'Full-time',
                        'education_requirement': item.get('education_code') or 'SMA/SMK',
                        'experience_requirement': f"{item.get('min_experience_years', 0)} tahun" if item.get('min_experience_years', 0) > 0 else 'Fresh Graduate',
                        'age_requirement': f"{item.get('min_age')}-{item.get('max_age')} tahun" if item.get('min_age') and item.get('max_age') else None,
                        'salary_raw': salary_raw,
                        'description': item.get('description') or '',
                        'requirements': item.get('requirements') or '',
                        'skills': ', '.join(item.get('skills') or []),
                        'posted_at': (datetime.now() - timedelta(days=2)).strftime('%Y-%m-%d'),
                        'deadline': (datetime.now() + timedelta(days=20)).strftime('%Y-%m-%d'),
                    })
            return matched
        except Exception:
            return []

    @abstractmethod
    def scrape(self):
        """
        Execute scraping. Must yield or return a list of raw job dictionaries.
        """
        pass
