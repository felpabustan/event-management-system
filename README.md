# Event Management System

A comprehensive Laravel-based event management system with user registration, admin controls, Stripe payment integration, and flexible content management.

## Features

### 🎫 Event Management
- Create and manage events with detailed information
- Set capacity limits and track registrations
- Automatic capacity management
- Event status tracking (available/full)

### 👥 User Registration System
- Public event registration with form validation
- Automatic email confirmations
- Registration management and tracking
- Check-in system with QR codes

### 💳 Payment Integration
- Stripe payment processing for paid events
- Secure checkout sessions
- Payment status tracking
- Manual refund processing with email notifications

### 🔐 Admin Panel
- Role-based access control (Super Admin vs Admin)
- User management system
- Registration deletion with notification system
- Comprehensive admin dashboard

### 📧 Email Notifications
- Registration confirmations for attendees
- Admin alerts for registration cancellations
- Detailed Stripe reference information for manual refunds
- Professional email templates

### 🏠 Content Management System
- Flexible homepage content blocks
- Hero sections with customizable content
- Text content with rich text editing
- Video embed support (YouTube/Vimeo)
- Image blocks with captions
- Events listing blocks
- **Custom HTML blocks for developers**

### 🎨 Custom HTML Content Blocks
- Raw HTML and CSS input for maximum flexibility
- Professional code editor interface
- Security warnings and best practices
- Tailwind CSS framework integration
- Responsive design support

## Technical Stack

- **Backend**: Laravel 12
- **Frontend**: Blade templating with Tailwind CSS
- **Database**: SQLite (configurable)
- **Payment**: Stripe integration
- **Email**: Laravel Mail system
- **Authentication**: Laravel Breeze

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/felpabustan/event-management-system.git
   cd event-management-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

## Configuration

### Environment Variables

```env
# Application
APP_NAME="Event Management System"
APP_URL=http://localhost

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password

# Stripe Configuration
STRIPE_KEY=your-stripe-public-key
STRIPE_SECRET=your-stripe-secret-key
```

### Admin User Setup

Create a super admin user:
```bash
php artisan db:seed --class=AdminUserSeeder
```

Default admin credentials:
- Email: admin@example.com
- Password: password

## Usage

### Admin Access
1. Navigate to `/login`
2. Use admin credentials
3. Access admin features through the dashboard

### Content Management
1. Go to Admin → Content Blocks
2. Create different types of content blocks:
   - Hero sections
   - Text content
   - Video embeds
   - Image blocks
   - Events listings
   - Custom HTML blocks

### Event Management
1. Create events with all necessary details
2. Set capacity and pricing
3. Monitor registrations
4. Manage check-ins

### Payment Processing
When registrations are cancelled:
1. Registrant receives cancellation email
2. Admins receive detailed refund instructions
3. Process refunds manually in Stripe Dashboard

## Custom HTML Blocks

The system supports custom HTML content blocks for developers:

```html
<!-- Example HTML Content -->
<div class="custom-section bg-gray-100 py-16">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-8">Custom Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Your content here -->
        </div>
    </div>
</div>
```

```css
/* Example CSS Styling */
.custom-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

See `docs/HTML_CONTENT_BLOCKS.md` for detailed documentation.

## Security

- Role-based access control
- CSRF protection
- Input validation and sanitization
- Secure payment processing
- HTML content security warnings

## API Endpoints

### Public Routes
- `GET /` - Homepage with content blocks
- `GET /events` - Public events listing
- `GET /events/{event}` - Event details
- `POST /events/{event}/register` - Event registration

### Admin Routes (Authentication Required)
- `GET /dashboard` - Admin dashboard
- `GET /admin/events` - Event management
- `GET /admin/users` - User management (Super Admin only)
- `GET /admin/homepage-content` - Content block management

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For technical support or questions:
- Check the documentation in the `docs/` directory
- Review the code comments for implementation details
- Test features in a development environment

## Changelog

### v1.0.0
- Initial release with core event management features
- User registration and payment processing
- Admin panel with role-based access
- Content management system
- Custom HTML content blocks
- Email notification system

---

Built with ❤️ using Laravel and Tailwind CSS
