# HPL Game Design Inventory System

A web-based Inventory Management System built using **Vanilla PHP**, **Bootstrap**, and **JQuery**.

- **Hosting Site**: [InfinityFree](https://www.infinityfree.com/)
- **Domain**: [HPL Game Design Inventory](https://hplgamedesigninventory.free.nf/)

## Overview

This system is designed to efficiently track IT assets, streamline inventory management, and facilitate computer allocation, transfers, and returns. It provides a centralized platform for organizations to monitor hardware assets, ensuring accountability, optimizing resource usage, and maintaining accurate records.

---

## Project Structure

The project is organized into several folders for easy maintenance and development:

- **`form/`**: Contains backend functions for form submissions.
- **`pages/`**: Contains all the pages of the website.
- **`server/`**: Contains server-side functions such as JQuery, dropdowns, connections, login, and logout.
- **`admin/`**: Contains admin-specific pages.
- **`js/`**: Contains JQuery scripts, each named according to their functionality.

All files include comments to guide developers on their functionality, making maintenance easier.

---

## Key Features

1. **Database Backup**: Allows administrators to back up the database.
2. **User Management**: Registered users can be viewed, approved, or deleted by the main administrator.
3. **Employee Data Upload**: 
   - Supports uploading employee data in `.tsv` (tab-separated values) format.
   - **Note**: Before importing, ensure the data is accurate, especially for special characters like `Ñ`, `ñ`, etc. It is recommended to view the file in Microsoft Excel before uploading.
4. **Manual Employee Registration**: Allows manual registration for one or two employees. For larger datasets, importing is recommended for accuracy.
5. **Real-Time Computer Name Check**: Ensures no duplicate computer names are added.
6. **Parts and Components Management**: Users can add as many parts and components as needed.
7. **Asset Allocation**:
   - Assign computers to employees.
   - Transfer computers, parts, or peripherals between employees.
   - Mark returned computers and log their history with the last user's data.

---

## Hosting and Deployment

This project is hosted on **InfinityFree** and does not require the installation of dependencies via `npm` or `composer`. All files are uploaded directly to the hosting platform.

### How to Manage

1. Sign in to **InfinityFree** and navigate to the **File Manager**.
2. Open the `htdocs` folder to access all project files.
3. Follow the folder structure described above to locate specific files or functionalities.

---

## Development Setup

To develop or modify the project locally, follow these steps:

1. **Install Required Tools**:
   - Install **Composer**, **PHP**, and **XAMPP** on your computer.
2. **Database Setup**:
   - Backup the database from the live website.
   - Import the database into **XAMPP**'s `phpMyAdmin`.
3. **Run the Project Locally**:
   - Open a command prompt and navigate to the project directory.
   - Run the command:  
     ```bash
     php -S localhost:8000
     ```
   - Access the project in your browser at `http://localhost:8000`.
4. **Deployment**:
   - Use **FileZilla** for faster uploading of changes to **InfinityFree**.
5. **Connection Configuration**:
   - Ensure the `server/connections.php` file contains the correct `host`, `username`, `password`, and `db_name` credentials matching those in **InfinityFree**.
   - **Always backup files before making changes** to avoid losing functionality.

---

## Notes for Developers

- The project is designed to be lightweight and does not require dependency management tools like `npm` or `composer`.
- Always test changes locally before deploying to the live site.
- Use the comments in the code to understand the functionality of each file.
- Ensure the database is imported into your local **phpMyAdmin** for smooth operation during development.

---

## License

This project is open-source and free to use. For more details, refer to the repository or contact the maintainers.

---

**Happy Coding!** 🚀