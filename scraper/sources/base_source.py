from abc import ABC, abstractmethod

class BaseSource(ABC):
    def __init__(self, source_record):
        self.source_record = source_record
        self.source_id = source_record['id']
        self.name = source_record['name']
        self.base_url = source_record['base_url']
        self.list_url = source_record['list_url']

    @abstractmethod
    def scrape(self):
        """
        Execute scraping. Must yield or return a list of raw job dictionaries:
        {
            'original_url': str,
            'title': str,
            'company': str,
            'location': str,
            'category': str or None,
            'employment_type': str or None,
            'education_requirement': str or None,
            'experience_requirement': str or None,
            'age_requirement': str or None,
            'salary_min': float or None,
            'salary_max': float or None,
            'salary_raw': str or None,
            'description': str,
            'requirements': str,
            'skills': list or str,
            'posted_at': str or None,
            'deadline': str or None
        }
        """
        pass
