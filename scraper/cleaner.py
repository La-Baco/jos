import re
from datetime import datetime
from bs4 import BeautifulSoup

class Cleaner:
    @staticmethod
    def clean_html(text):
        if not text:
            return ""
        # Remove script and style elements
        soup = BeautifulSoup(text, "html.parser")
        for element in soup(["script", "style", "noscript"]):
            element.extract()
        clean_text = soup.get_text(separator="\n")
        # Collapse multiple empty lines
        lines = [line.strip() for line in clean_text.splitlines()]
        clean_text = "\n".join([line for line in lines if line])
        return clean_text.strip()

    @staticmethod
    def clean_whitespace(text):
        if not text:
            return ""
        return re.sub(r'\s+', ' ', str(text)).strip()

    @staticmethod
    def parse_salary(text):
        if not text:
            return None, None, None

        raw_str = str(text).strip()
        lower_str = raw_str.lower()

        # 1. Check for "juta" / "jt" shorthand (e.g., 2.5 jt - 3.5 juta)
        juta_matches = re.findall(r'(\d+(?:[.,]\d+)?)\s*(?:jt|juta)', lower_str)
        if len(juta_matches) >= 2:
            val1 = float(juta_matches[0].replace(',', '.')) * 1_000_000
            val2 = float(juta_matches[1].replace(',', '.')) * 1_000_000
            return min(val1, val2), max(val1, val2), raw_str
        elif len(juta_matches) == 1:
            val1 = float(juta_matches[0].replace(',', '.')) * 1_000_000
            return val1, None, raw_str

        # 2. Check for standard numeric digits (e.g., Rp 2.500.000 - Rp 3.500.000)
        cleaned = raw_str.replace('.', '').replace(',', '')
        numbers = re.findall(r'\d{6,8}', cleaned)

        if len(numbers) >= 2:
            s_min = float(numbers[0])
            s_max = float(numbers[1])
            if s_min > s_max:
                s_min, s_max = s_max, s_min
            return s_min, s_max, raw_str
        elif len(numbers) == 1:
            s_min = float(numbers[0])
            return s_min, None, raw_str

        # 3. Detect UMK / UMR Sumenep reference (UMK Sumenep ~ Rp 2.350.000)
        if 'umk' in lower_str or 'umr' in lower_str:
            return 2350000.0, None, raw_str

        return None, None, raw_str if raw_str else None

    @staticmethod
    def parse_date(date_str):
        if not date_str:
            return None

        clean_str = date_str.strip()
        formats = [
            "%Y-%m-%d",
            "%d-%m-%Y",
            "%d/%m/%Y",
            "%Y/%m/%d",
            "%d %b %Y",
            "%d %B %Y",
        ]

        # Indonesian month replacements
        bulan_map = {
            'januari': 'january', 'februari': 'february', 'maret': 'march',
            'april': 'april', 'mei': 'may', 'juni': 'june',
            'juli': 'july', 'agustus': 'august', 'september': 'september',
            'oktober': 'october', 'november': 'november', 'desember': 'december'
        }
        lower_str = clean_str.lower()
        for indo, eng in bulan_map.items():
            if indo in lower_str:
                lower_str = lower_str.replace(indo, eng)
                break

        for fmt in formats:
            try:
                return datetime.strptime(lower_str, fmt).date()
            except ValueError:
                continue

        return None
