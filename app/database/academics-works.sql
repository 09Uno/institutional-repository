CREATE TABLE IF NOT EXISTS academics_works (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  advisor VARCHAR(255) NOT NULL,
  abstract TEXT NOT NULL,
  keywords VARCHAR(255) NOT NULL,
  presentation_date DATE ,
  research_area VARCHAR(255) NOT NULL
);