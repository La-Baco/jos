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

    @abstractmethod
    def scrape(self):
        """
        Execute scraping. Must yield or return a list of raw job dictionaries.
        """
        pass
