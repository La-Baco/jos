import os
import pymysql
from datetime import datetime

class Database:
    def __init__(self):
        # Load from .env if possible or default to local Laragon MySQL
        self.host = os.getenv('DB_HOST', '127.0.0.1')
        self.port = int(os.getenv('DB_PORT', 3306))
        self.user = os.getenv('DB_USERNAME', 'root')
        self.password = os.getenv('DB_PASSWORD', '')
        self.database = os.getenv('DB_DATABASE', 'jos')
        self.conn = None

    def connect(self):
        if not self.conn or not self.conn.open:
            self.conn = pymysql.connect(
                host=self.host,
                port=self.port,
                user=self.user,
                password=self.password,
                database=self.database,
                charset='utf8mb4',
                cursorclass=pymysql.cursors.DictCursor,
                autocommit=True
            )
        return self.conn

    def close(self):
        if self.conn and self.conn.open:
            self.conn.close()

    def get_active_sources(self):
        conn = self.connect()
        with conn.cursor() as cursor:
            cursor.execute("SELECT * FROM scraping_sources WHERE is_active = 1")
            return cursor.fetchall()

    def create_log(self, source_id):
        conn = self.connect()
        with conn.cursor() as cursor:
            cursor.execute("""
                INSERT INTO scraping_logs (scraping_source_id, started_at, status, jobs_found, jobs_new, jobs_updated, duplicates, created_at, updated_at)
                VALUES (%s, %s, 'running', 0, 0, 0, 0, %s, %s)
            """, (source_id, datetime.now(), datetime.now(), datetime.now()))
            return cursor.lastrowid

    def update_log(self, log_id, status, jobs_found, jobs_new, jobs_updated, duplicates, error_message=None):
        conn = self.connect()
        with conn.cursor() as cursor:
            cursor.execute("""
                UPDATE scraping_logs
                SET status = %s, finished_at = %s, jobs_found = %s, jobs_new = %s, jobs_updated = %s, duplicates = %s, error_message = %s, updated_at = %s
                WHERE id = %s
            """, (status, datetime.now(), jobs_found, jobs_new, jobs_updated, duplicates, error_message, datetime.now(), log_id))

    def update_source_last_scraped(self, source_id):
        conn = self.connect()
        with conn.cursor() as cursor:
            cursor.execute("""
                UPDATE scraping_sources
                SET last_scraped_at = %s, updated_at = %s
                WHERE id = %s
            """, (datetime.now(), datetime.now(), source_id))

    def get_existing_job_by_url_or_hash(self, url_hash, content_hash):
        conn = self.connect()
        with conn.cursor() as cursor:
            cursor.execute("""
                SELECT id, original_url, url_hash, content_hash FROM jobs
                WHERE url_hash = %s OR content_hash = %s
                LIMIT 1
            """, (url_hash, content_hash))
            return cursor.fetchone()

    def get_categories(self):
        conn = self.connect()
        with conn.cursor() as cursor:
            cursor.execute("SELECT id, name, slug FROM job_categories")
            return cursor.fetchall()

    def get_education_levels(self):
        conn = self.connect()
        with conn.cursor() as cursor:
            cursor.execute("SELECT id, name, code, hierarchy_order FROM education_levels ORDER BY hierarchy_order")
            return cursor.fetchall()

    def get_master_skills_with_aliases(self):
        conn = self.connect()
        with conn.cursor() as cursor:
            cursor.execute("""
                SELECT s.id, s.name, sa.alias
                FROM skills s
                LEFT JOIN skill_aliases sa ON sa.skill_id = s.id
            """)
            return cursor.fetchall()

    def save_job(self, job_data, skill_ids=None):
        conn = self.connect()
        now = datetime.now()
        with conn.cursor() as cursor:
            existing = self.get_existing_job_by_url_or_hash(job_data['url_hash'], job_data['content_hash'])

            if existing:
                job_id = existing['id']
                cursor.execute("""
                    UPDATE jobs SET
                        title = %s, normalized_title = %s, company = %s, location = %s,
                        normalized_regency = %s, normalized_district = %s, is_remote = %s,
                        job_category_id = %s, employment_type = %s, education_level_id = %s,
                        min_experience_years = %s, min_age = %s, max_age = %s,
                        salary_min = %s, salary_max = %s, salary_notes = %s,
                        description = %s, requirements = %s, raw_skills = %s,
                        posted_at = %s, deadline = %s, status = 'active', scraped_at = %s, updated_at = %s
                    WHERE id = %s
                """, (
                    job_data['title'], job_data['normalized_title'], job_data['company'], job_data['location'],
                    job_data['normalized_regency'], job_data['normalized_district'], job_data['is_remote'],
                    job_data['job_category_id'], job_data['employment_type'], job_data['education_level_id'],
                    job_data['min_experience_years'], job_data['min_age'], job_data['max_age'],
                    job_data['salary_min'], job_data['salary_max'], job_data.get('salary_notes'),
                    job_data['description'], job_data['requirements'], job_data.get('raw_skills'),
                    job_data['posted_at'], job_data['deadline'], now, now, job_id
                ))
                action = 'updated'
            else:
                cursor.execute("""
                    INSERT INTO jobs (
                        scraping_source_id, original_url, url_hash, content_hash,
                        title, normalized_title, company, location, normalized_regency, normalized_district,
                        is_remote, job_category_id, employment_type, education_level_id,
                        min_experience_years, min_age, max_age, salary_min, salary_max, salary_notes,
                        description, requirements, raw_skills, posted_at, deadline, status, scraped_at, created_at, updated_at
                    ) VALUES (
                        %s, %s, %s, %s,
                        %s, %s, %s, %s, %s, %s,
                        %s, %s, %s, %s,
                        %s, %s, %s, %s, %s, %s,
                        %s, %s, %s, %s, %s, 'active', %s, %s, %s
                    )
                """, (
                    job_data['scraping_source_id'], job_data['original_url'], job_data['url_hash'], job_data['content_hash'],
                    job_data['title'], job_data['normalized_title'], job_data['company'], job_data['location'],
                    job_data['normalized_regency'], job_data['normalized_district'], job_data['is_remote'],
                    job_data['job_category_id'], job_data['employment_type'], job_data['education_level_id'],
                    job_data['min_experience_years'], job_data['min_age'], job_data['max_age'],
                    job_data['salary_min'], job_data['salary_max'], job_data.get('salary_notes'),
                    job_data['description'], job_data['requirements'], job_data.get('raw_skills'),
                    job_data['posted_at'], job_data['deadline'], now, now, now
                ))
                job_id = cursor.lastrowid
                action = 'new'

            # Sync skills
            if skill_ids:
                cursor.execute("DELETE FROM job_skills WHERE job_id = %s", (job_id,))
                for s_id in skill_ids:
                    cursor.execute("""
                        INSERT IGNORE INTO job_skills (job_id, skill_id, is_required, created_at, updated_at)
                        VALUES (%s, %s, 1, %s, %s)
                    """, (job_id, s_id, now, now))

            return action, job_id
