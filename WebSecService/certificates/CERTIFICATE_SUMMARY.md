# Certificate Authority and Certificates Summary

## Root Certificate Authority (CA)
**Name:** Final Exam Root
**Files:** `ca.crt` and `ca.key`
**Validity:** 10 years (June 1, 2025 - May 30, 2035)
**Key Size:** 4096-bit RSA
**Subject:** C=US, ST=California, L=San Francisco, O=Final Exam Organization, OU=IT Department, CN=Final Exam Root

## Website Certificate
**Domain:** www.final-exam.com
**Files:** `www.final-exam.com.crt` and `www.final-exam.com.key`
**Validity:** 1 year (June 1, 2025 - June 1, 2026)
**Key Size:** 2048-bit RSA
**Subject:** C=US, ST=California, L=San Francisco, O=Final Exam Organization, OU=Web Services, CN=www.final-exam.com
**Subject Alternative Names:** 
- DNS: www.final-exam.com
- DNS: final-exam.com

## User Certificate
**User:** 230101033@final-exam.com
**Files:** `230101033@final-exam.com.crt` and `230101033@final-exam.com.key`
**Validity:** 3 months (June 1, 2025 - August 30, 2025)
**Key Size:** 2048-bit RSA
**Subject:** C=US, ST=California, L=San Francisco, O=Final Exam Organization, OU=Students, CN=230101033@final-exam.com, emailAddress=230101033@final-exam.com
**Subject Alternative Names:** 
- email: 230101033@final-exam.com

## File Structure
```
certificates/
├── ca.crt                              # Root CA certificate (public)
├── ca.key                              # Root CA private key (KEEP SECURE!)
├── ca.srl                              # Serial number file for CA
├── www.final-exam.com.crt              # Website certificate (public)
├── www.final-exam.com.key              # Website private key
├── www.final-exam.com.csr              # Website certificate signing request
├── www.final-exam.com.cnf              # Website certificate configuration
├── www.final-exam.com.ext              # Website certificate extensions
├── 230101033@final-exam.com.crt          # User certificate (public)
├── 230101033@final-exam.com.key          # User private key
├── 230101033@final-exam.com.csr          # User certificate signing request
├── 230101033@final-exam.com.cnf          # User certificate configuration
├── 230101033@final-exam.com.ext          # User certificate extensions
└── 230101033@final-exam.com.pfx          # User certificate in PKCS#12 format
```

## Usage Instructions

### To verify certificates:
```bash
# Verify website certificate against CA
openssl verify -CAfile ca.crt www.final-exam.com.crt

# Verify user certificate against CA
openssl verify -CAfile ca.crt 230101033@final-exam.com.crt

# View certificate details
openssl x509 -in <certificate-file> -text -noout

# Import user certificate to browser (use the .pfx file)
# Password for .pfx file: password123
```

### To create additional certificates:
1. Generate private key
2. Create CSR (Certificate Signing Request)
3. Sign with Root CA using the same process

## Security Notes
- Keep `ca.key` highly secure - it can sign any certificate
- Website certificate expires in 1 year (June 1, 2026)
- User certificate expires in 3 months (August 30, 2025)
- All certificates use SHA-256 with RSA encryption
- Root CA is valid for 10 years and can sign additional certificates as needed
- User certificate is available in PKCS#12 format (.pfx) for easy browser import

## Certificate Chain
```
Final Exam Root (Root CA)
├── www.final-exam.com (Website Certificate)
└── 230101033@final-exam.com (User Certificate)
```
