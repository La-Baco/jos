import hashlib

class Deduplicator:
    @staticmethod
    def generate_url_hash(url):
        if not url:
            return None
        return hashlib.sha256(url.strip().lower().encode('utf-8')).hexdigest()

    @staticmethod
    def generate_content_hash(title, company, location):
        raw = f"{title}|{company}|{location}".strip().lower()
        return hashlib.sha256(raw.encode('utf-8')).hexdigest()

    def __init__(self, db):
        self.db = db
        self.seen_urls = set()
        self.seen_content = set()

    def is_duplicate(self, url_hash, content_hash):
        if url_hash in self.seen_urls or content_hash in self.seen_content:
            return True

        # Check in DB
        existing = self.db.get_existing_job_by_url_or_hash(url_hash, content_hash)
        if existing:
            self.seen_urls.add(url_hash)
            self.seen_content.add(content_hash)
            return True

        self.seen_urls.add(url_hash)
        self.seen_content.add(content_hash)
        return False
