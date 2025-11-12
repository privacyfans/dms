# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Document Management System (DMS)** for Bank Woori Saudara, built with Laravel 8 and Livewire. The system manages loan application documents through a multi-stage approval workflow (SPV1 → SPV2 → SPV3) with AI-powered document validation and comprehensive SLA monitoring.

## Development Commands

### PHP/Laravel Commands
- `php artisan serve` - Start the development server (typically runs on http://127.0.0.1:8000)
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed the database
- `php artisan tinker` - Open Laravel REPL for debugging
- `php artisan config:cache` - Cache configuration files
- `php artisan route:list` - List all registered routes

### Testing
- `vendor/bin/phpunit` - Run PHPUnit tests
- `php artisan test` - Run tests using Laravel's test runner

### Frontend Build Commands
- `npm install` - Install Node.js dependencies
- `npm run dev` - Build assets for development
- `npm run watch` - Watch for changes and rebuild assets
- `npm run hot` - Hot module replacement for faster development
- `npm run prod` - Build assets for production

### Composer
- `composer install` - Install PHP dependencies
- `composer dump-autoload` - Regenerate autoload files

## Core Architecture

### Business Domain
The application handles loan document processing for various Indonesian banking products (KUP, KUPEG, KPH, THT, KPKB, etc.) with:

- **Multi-level approval workflow**: SPV1 → SPV2 → SPV3 supervisory levels
- **AI-powered document validation**: Automated scoring and quality checks
- **TBO (To Be Override) management**: Exception handling for non-standard cases
- **SLA monitoring**: Performance tracking and reporting
- **Multi-branch operations**: Hierarchical branch access control

### Key Models and Their Purpose

**Core Business Models:**
- `DataFileModel` - Central loan application entity with approval workflow
- `DetailFileModel` - Document attachments with AI validation scores
- `Comment` - Review comments and TBO management
- `User` - Role-based user management with branch access control

**Operational Models:**
- `CutOff`/`CutOffHq` - Service hour management and upload controls
- `PerpanjanganJam`/`PerpanjanganTbo` - Extension request workflows
- `Branchlist` - Branch hierarchy and access control
- `SlaBpr`/`Registration` - SLA monitoring and process tracking

### Controllers Structure

**Primary Controllers:**
- `DataFilesController` - Main loan processing workflow (routes: `/loans/*`)
- `HomeController` - Dashboard and analytics (routes: `/`, `/chart`)
- `LoanSearchController` - Document search and history (routes: `/loan-search-history/*`)

**Administrative Controllers:**
- `UserController` - User and branch management
- `CutOffController`/`CutOffHqController` - Service hour controls
- `PerpanjanganJamController`/`PerpanjanganTboController` - Extension workflows

### File Structure Notes

- **Livewire Components**: Extensive use of Livewire for interactive UI components located in `app/Http/Livewire/`
- **FTP Integration**: Document storage on remote FTP server (172.28.97.30)
- **Custom Middleware**: `checkRole` and `checkUserLogin` for access control
- **Laravel Mix**: Complex asset compilation setup in `webpack.mix.js` with 50+ JS/SASS files

### Database Architecture

- Uses MySQL with complex relationships between loans, documents, users, and branches
- Primary keys often use business identifiers (NIK for users, loan_app_no for applications)
- Extensive use of status flags and multi-level approval tracking
- AI scoring integration for document quality assessment

### Authentication and Authorization

- Custom authentication middleware (`checkUserLogin`)
- Role-based access control with branch-level permissions
- Integration with external EHR system for login
- Multi-level supervisor hierarchy (SPV1, SPV2, SPV3, PC, PCP roles)

## Development Guidelines

### Working with Loans and Documents
- Loan applications are identified by `loan_app_no`
- Documents go through validation pipeline with AI scoring
- Always consider the multi-level approval workflow when modifying loan processing
- TBO (To Be Override) functionality requires special handling for exceptions

### Branch and User Management
- Users are identified by NIK (Indonesian National ID)
- Branch hierarchy affects data access and permissions
- Role assignments determine available features and approval levels

### File Upload and Storage
- Documents stored on FTP server with structured directory paths
- File validation includes AI-powered quality scoring
- Support for ZIP download of multiple documents
- Integration with `DetailFileModel` for metadata tracking

### SLA and Performance Monitoring
- Extensive reporting capabilities for performance metrics
- Real-time dashboard updates with server time synchronization
- Branch-wise and product-wise analytics
- Cut-off time controls for operational hours

## Important Notes

- The application uses Indonesian language and banking terminology
- Extensive Livewire component library for UI elements
- Complex approval workflows with multiple supervisor levels
- Integration with external FTP server for document storage
- AI-powered document validation and scoring system
- Multi-branch architecture with hierarchical permissions