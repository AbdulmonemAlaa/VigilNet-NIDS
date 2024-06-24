# VigilNet-NIDS

## Signature-Based Intrusion Detection System

This project is a senior project submitted in partial fulfillment of the requirements for the degree of Bachelor of Computers and Artificial Intelligence in the “Information Security and Digital Forensics” Program at Benha University.

### Project Team

1. Abdulmonem Alaa Aldeen Abdulmonem
2. Ziad Walid Mohammed Reda
3. Ahmed Ayman Abdelaziz Morad
4. Hakim Samir Hakim Habib
5. Khalid Said Abdelmaboud
6. Youssef Mohammed Youssef Elkady
7. Abdelrahman Abd El Mawla Elsayed

Under the supervision of Dr. Mohammed Taha

### Abstract

The Signature-Based Intrusion Detection System (IDS) identifies and mitigates known threats through pattern matching techniques. The system analyzes network traffic and system activities against a comprehensive database of signatures to detect and alert specific attacks in real-time, minimizing potential damage. The architecture is designed for scalability, allowing regular updates to address emerging threats. The IDS features adaptive mechanisms, efficient algorithms, and optimized data structures to balance high throughput with minimal latency, ensuring real-time network traffic handling.

### Features

- Real-time monitoring and historical data analysis.
- Scalable architecture with regular updates to the signature database.
- High throughput and minimal latency.
- User-friendly interface for managing signatures, analyzing alerts, and configuring detection parameters.
- Extensive logging and reporting for comprehensive insights and swift incident response.
- High accuracy in threat detection with minimal false positives.

### Technologies Used

- Programming Languages: Python
- Database Management: MySQL
- Development Tools: PyCharm, PhpStorm, XAMPP
- Web Technologies: HTML, CSS, JavaScript, PHP, Laravel

### Installation
1. Clone the Repository:
  - git clone https://github.com/AbdulmonemAlaa/VigilNet-NIDS.git
  - cd VigilNet-NIDS
2. Set Up the Database:
  - Use MySQL and phpMyAdmin to create and manage the database.
  - Import the database schema provided in the database/schema.sql file.
3. Configure the Environment:
  - Update the .env file with your database credentials and other configuration details.
4. Install Dependencies:
  - pip install -r requirements.txt
5. Run the Application:
  - python manage.py runserver
## Usage
### Login:
  Default Username: admin@gmail.com
  Default Password: securepassword123
  Change the default password after the first login.

### Features:
1. User Management: Create, modify, and manage user accounts.
2. System Configuration: Manage and customize detection rules and system configuration.
3. Monitoring: Conduct real-time network activity analysis with options for filtering and clearing data.
4. Real-Time Alerting: Receive immediate notifications of critical network events.
5. Reporting: Generate detailed security reports and analyze historical data.
6. Dashboard: Provides statistical network insights visualized through charts.

### Limitations
1. Dependency on Known Signatures: Unable to identify novel or zero-day attacks.
2. False Positives and Negatives: Balancing these rates is crucial for optimal performance.
3. Performance and Scalability: High-traffic environments may strain the system's capabilities.
4. Integration Issues: Compatibility with existing network infrastructures may pose challenges.
5. Maintenance: Requires regular updates to maintain effectiveness against new threats.
   
### Future Recommendations
1. Integration of Machine Learning and AI: Enhance threat detection capabilities and reduce false positives.
2. Expansion to Anomaly-Based Detection: Provide a more comprehensive defense mechanism.
3. Enhanced Scalability: Optimize performance for large volumes of data.
4. Regular Updates and Maintenance Protocols: Ensure the system remains effective against new threats.

### References
1. Anderson, J. P. (1980). Computer Security Threat Monitoring and Surveillance.
2. Axelsson, S. (2000). Intrusion Detection Systems: A Survey and Taxonomy.
3. Denning, D. E. (1987). An Intrusion-Detection Model.
4. Scarfone, K., & Mell, P. (2007). Guide to Intrusion Detection and Prevention Systems (IDPS).
5. Roesch, M. (1999). Snort - Lightweight Intrusion Detection for Networks.
6. Kumar, S., & Spafford, E. H. (1994). An Application of Pattern Matching in Intrusion Detection.
7. Lunt, T. F. (1993). A Survey of Intrusion Detection Techniques.
### Acknowledgment
We would like to express our great appreciation to Dr. Mohammed Taha for his valuable suggestions during the planning and development of this project.
