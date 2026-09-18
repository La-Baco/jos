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

        cleaned = str(text).replace('.', '').replace(',', '')
        numbers = re.findall(r'\d{6,8}', cleaned)

        if len(numbers) >= 2:
            s_min = float(numbers[0])
            s_max = float(numbers[1])
            if s_min > s_max:
                s_min, s_max = s_max, s_min
            return s_min, s_max, text.strip()
        elif len(numbers) == 1:
            s_min = float(numbers[0])
            return s_min, None, text.strip()

        return None, None, text.strip() if text else None

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
