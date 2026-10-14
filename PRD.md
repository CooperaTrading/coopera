PRODUCTION REQUIREMENT DOCUMENT (PRD)
Project: Cooper Trading Website Upgrade
1. Project Overview
Upgrading the existing Cooper Trading website (https://www.cooperatrading.com/) to a modern, responsive platform. The redesign will draw inspiration from Access Ethiopia Trading, Tiru Trading, and Tamrin. The core addition is a Minimal CMS admin panel to manage products/categories and generate automated PDF Proforma Invoices based on customer quote requests.

2. Technology Stack
Backend: Laravel (PHP 8.2+)
Frontend: Blade Templates, Tailwind CSS, Alpine.js
Database: MySQL (Hosted on Yegar cPanel)
PDF Generation: barryvdh/laravel-dompdf
Email: Laravel Mail (SMTP via cPanel or external SMTP)
3. Core Features & User Flows
A. Public Website (Frontend)
Pages: Home, About Us, Contact Us, Products Catalog. (Content for Home, About, and Contact will be hardcoded in Blade files).
Product Catalog: Categorized view of products. Customers can browse categories (e.g., Construction Products, Industrial Chemicals) and view product details (Image, Name, Description, Unit of Measure).
Request a Quote Form: Accessible on the site. Customers fill out: Name, Company, Email, Phone, and a multi-select dropdown (Alpine.js) to pick products from the catalog and enter quantities.
Design: Modern B2B trading aesthetic, heavy use of whitespace, professional typography, responsive for mobile/tablet.
B. Admin Panel (Backend - Secret URL /admin)
Authentication: Simple username/password login (Laravel Breeze or custom).
Dashboard: Lists recent Quote Requests.
Category Management: CRUD for product categories.
Product Management: CRUD for products. Fields: Name, Category, Image, Short Description, Unit of Measure (piece, box, kg, ton).
Quote Requests Management: View list of submitted quote requests with customer details and requested products.
Proforma Generator:
Admin opens a Quote Request and clicks "Create Proforma".
Admin inputs Unit Prices for the requested items.
System auto-calculates Totals and adds 15% VAT.
Admin inputs Payment Terms, Delivery Time, and Bank Details.
System auto-generates a Proforma Invoice Number and Validity Date (30 days).
Admin clicks "Generate & Send".
System generates a PDF using DomPDF and emails it directly to the customer's email address.
4. Database Schema (High-Level)
categories: id, name, description
products: id, category_id, name, slug, image, description, unit_of_measure
quote_requests: id, customer_name, company_name, email, phone, message, status (pending, processed)
quote_request_items: id, quote_request_id, product_id, quantity
proformas: id, quote_request_id, proforma_number (auto), validity_date, payment_terms, delivery_time, bank_details, subtotal, vat (15%), total
proforma_items: id, proforma_id, product_id, quantity, unit_price, total_price
🚀 SUBTASK-BASED PLAYBOOK FOR ANTIGRAVITY IDE AI
Copy and paste these tasks sequentially into your Antigravity IDE AI.

Task 1: Project Initialization & Database Schema
Prompt: "Initialize a new Laravel project named cooperatrading. Set up Laravel Breeze for simple authentication. Configure the .env for a MySQL database. Next, create migrations and models for the following schema:

Category (id, name, description)
Product (id, category_id, name, slug, image, description, unit_of_measure)
QuoteRequest (id, customer_name, company_name, email, phone, message, status enum['pending','processed'])
QuoteRequestItem (id, quote_request_id, product_id, quantity)
Proforma (id, quote_request_id, proforma_number, validity_date, payment_terms, delivery_time, bank_details, subtotal, vat, total)
ProformaItem (id, proforma_id, product_id, quantity, unit_price, total_price)
Set up the appropriate model relationships (hasMany, belongsTo)."
Task 2: Database Seeder for Products
Prompt: "Create a database seeder in Laravel to populate the categories and products tables. Use the following data:
Categories & Products:

Construction Product Markets (Concrete Solutions, Waterproofing Systems, Adhesives & Sealants, Concrete Repair & Protection, Flooring Systems, Roofing Systems, Geosynthetics, Expansion Joints & Joint Sealants)
Industrial Chemicals (Plasticizers, PVC Resins, PVC Stabilizers, Solvents, Fillers, Processing Aids, Lubricants, Pigments & Color Masterbatches)
Water & Environmental Solutions (Water Treatment Chemicals, Water Treatment Equipment, Industrial Filtration, Pumps & Flow Control, Environmental Protection Systems)
Packaging & Industrial Supplies (Industrial Packaging, Plastic Films, Packaging Materials, Industrial Consumables, Safety Equipment (PPE))
Industrial Technology Solutions (Industrial Automation, Electrical & Power Systems, Instrumentation & Control, Industrial Safety & Security, Smart Infrastructure)
Export Products (Coffee Export, Sesame & Oilseeds, Pulses & Spices, Natural Products)
Set Unit of Measure to 'piece' or 'kg' as default for all. Run the seeder."
Task 3: Admin Panel - Categories & Products CRUD
Prompt: "Create an admin panel under the route prefix /admin protected by Laravel authentication.

Create controllers and Blade views for Categories (CRUD).
Create controllers and Blade views for Products (CRUD).
For the Product creation form, include fields for Name, Category (dropdown), Image upload (store in public/products), Description (textarea), and Unit of Measure.
Use Tailwind CSS for styling the admin panel to look clean and modern.
Ensure the admin can view, add, edit, and delete categories and products."
Task 4: Frontend - Catalog & Layout
Prompt: "Create the public-facing frontend layout using Tailwind CSS and Blade layouts.

Create a main layout with a Navbar (Home, About Us, Products, Contact Us) and a Footer.
Create a Products page that lists the 6 categories. When a user clicks a category, it shows the products under that category in a grid layout (Image, Name, Description, UoM).
Create hardcoded Blade views for 'Home', 'About Us', and 'Contact Us' with placeholder B2B trading text and images.
Design inspiration: Clean, professional, lots of whitespace, similar to modern B2B industrial sites."
Task 5: Frontend - Request a Quote Form
Prompt: "Create a 'Request a Quote' page/form on the frontend.

The form should collect: Customer Name, Company Name, Email, Phone, and Message.
Use Alpine.js to create a multi-select interface where the user can search and select multiple products from the database, and input a 'Quantity' for each selected product.
On submit, store the data in the quote_requests table and the selected items in quote_request_items. Set status to 'pending'.
Show a success message to the user upon submission."
Task 6: Admin Panel - Quote Requests Management
Prompt: "Create an admin dashboard section for viewing Quote Requests.

List all quote requests under /admin/quote-requests showing Customer Name, Date, and Status.
Create a detail view where the admin can see the customer info and the list of requested products and quantities.
Add a button labeled 'Generate Proforma' that takes the admin to the Proforma creation page."
Task 7: Admin Panel - Proforma Generation & PDF
Prompt: "Install barryvdh/laravel-dompdf. Create the Proforma generation flow:

When the admin clicks 'Generate Proforma' from a Quote Request, create a new Proforma record linked to that Quote Request.
Create a form where the admin can input 'Unit Price' for each requested product.
Add fields for Admin to input: Payment Terms, Delivery Time, and Bank Details.
On submit, auto-calculate the Subtotal, add 15% VAT, and calculate the Total. Auto-generate a Proforma Number (e.g., PROF-0001) and set Validity Date to 30 days from now.
Generate a PDF using DomPDF using a clean, professional Proforma Invoice Blade template (including Cooper Trading placeholder logo, customer details, table of items, totals, VAT, and bank details).
Save the Proforma in the database and change the Quote Request status to 'processed'."
Task 8: Automated Email System
Prompt: "Set up Laravel Mail to send the generated Proforma to the customer.

Create a Mailable class that takes the Proforma data.
Create an email Blade template that says 'Dear [Customer Name], please find attached your proforma invoice from Cooper Trading.'
Attach the generated PDF from Task 7 to this email.
Trigger this email to send to the customer's email address when the admin finalizes the proforma.
Ensure a basic SMTP driver is configured in .env (leave placeholders for credentials)."