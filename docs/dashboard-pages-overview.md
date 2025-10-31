# Dashboard page changes and navigation

This guide lists the dashboard screens updated in the "Improve user management UX and service type workflows" change set and explains how to reach them in a local development environment.

## Prerequisites

1. Install dependencies and set up the Laravel application as described in the main `README.md`.
2. Start the dev server: `php artisan serve` (or use your existing container/web server setup).
3. Sign in with an account that has dashboard access (e.g., an administrator).
4. Visit the page paths listed below after the `/dashboard` prefix (for example, `http://localhost:8000/dashboard/users/profile`).

## User profile management

- **View template:** `resources/views/dashboard/users/profile/index.blade.php`
- **What changed:** Required indicators on core fields, optional personal/contact fields, and a "Delete profile" button wired to `ProfileController::destroy`.
- **How to view:** In the sidebar choose **Users → Profile** or browse to `/dashboard/users/profile` after signing in.

## User creation & editing forms

- **View templates:**
  - `resources/views/dashboard/users/form.blade.php`
  - `resources/views/dashboard/nurses/form.blade.php`
  - `resources/views/dashboard/physicians/form.blade.php`
  - `resources/views/dashboard/doctors/form.blade.php`
  - `resources/views/dashboard/clients/form.blade.php`
  - `resources/views/dashboard/clients/contacts/form.blade.php`
  - `resources/views/dashboard/service-providers/form.blade.php`
- **What changed:** Only the essential study information (first name, last name, email, position/role) is marked as required; other details remain optional.
- **How to view:** From the sidebar, open the matching resource (Users, Nurses, Physicians, Doctors, Clients, Client Contacts, or Service Providers) and click **Add** or **Edit**. Each action loads the corresponding form modal or page.

## Required-field indicator styling

- **Stylesheet:** `resources/sass/admin.scss`
- **JavaScript helper:** `public/js/general.js`
- **What changed:** A `.required-label::after` rule injects an asterisk on required labels, and the global form helper adds the CSS class to labels that need it.
- **How to view:** After recompiling assets (`npm run dev`), open any of the updated forms listed above and confirm the asterisk next to required fields.

## Service provider type management

- **Controller:** `app/Http/Controllers/ServiceProviderTypeController.php`
- **View templates:**
  - `resources/views/dashboard/service-providers/service_provider_types/index.blade.php`
  - `resources/views/dashboard/service-providers/service_provider_types/form.blade.php`
  - `resources/views/dashboard/service-providers/service_types/index.blade.php`
- **What changed:** Coordinators with the manage permission can add, edit, or delete service provider types, create custom titles through the "Other" option, and see DataTable actions for each type.
- **How to view:** Navigate to **Service Providers** → select a provider → **Service Provider Types** (path: `/dashboard/service-providers/{serviceProvider}/service_provider_types`). The modal shown from the **Add new Service provider type** button uses the updated form template.

## Sidebar navigation update

- **View template:** `resources/views/dashboard/layout/sidebar.blade.php`
- **What changed:** The Countries menu entry was removed while leaving country selection available inside individual forms.
- **How to view:** Open the dashboard sidebar after signing in; the Countries item no longer appears.

