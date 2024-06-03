
## Running the Project

1. Ensure you have Docker installed on your machine.
2. Navigate to the project directory in your terminal.
3. Run the following command to start the project using Docker Compose:

```bash
docker-compose up
```

4. Once the project is running, it will be accessible at [http://application.local](http://application.local).

**Important:** Make sure to add the following entry to your `/etc/hosts` file to map `application.local` to your localhost:

```plaintext
127.0.0.1 application.local
```

## API Endpoints

Below is a list of available API endpoints along with their descriptions:

### Token Endpoint

- **POST** `/api/v1/token`
    - Description: Obtain a token for authentication.
    - Form Data:
        - `username`: User 0
        - `password`: test
    - Note: The returned token should be included in the headers of all subsequent requests.

### Company Endpoints

- **GET** `/api/company/`
    - Description: Retrieve a list of all companies along with their associated projects and employees.

- **POST** `/api/company/`
    - Description: Create a new company.
    - Request Body Example:
      ```json
      {
        "name": "New Company"
      }
      ```

- **GET** `/api/company/{id}`
    - Description: Retrieve details of a specific company by its ID along with its projects and employees.
    - URL Parameter:
        - `id` (integer): The ID of the company to retrieve.

- **PUT** `/api/company/{id}`
    - Description: Update details of a specific company by its ID.
    - URL Parameter:
        - `id` (integer): The ID of the company to update.
    - Request Body Example:
      ```json
      {
        "name": "Updated Company Name"
      }
      ```

- **DELETE** `/api/company/{id}`
    - Description: Delete a specific company by its ID.
    - URL Parameter:
        - `id` (integer): The ID of the company to delete.

### Project Endpoints

- **GET** `/api/project/`
    - Description: Retrieve a list of all projects.

- **POST** `/api/project/`
    - Description: Create a new project.
    - Request Body Example:
      ```json
      {
        "name": "New Project",
        "companyId": 1
      }
      ```

- **GET** `/api/project/{id}`
    - Description: Retrieve details of a specific project by its ID.
    - URL Parameter:
        - `id` (integer): The ID of the project to retrieve.

- **PUT** `/api/project/{id}`
    - Description: Update details of a specific project by its ID.
    - URL Parameter:
        - `id` (integer): The ID of the project to update.
    - Request Body Example:
      ```json
      {
        "name": "Updated Project Name"
      }
      ```

- **DELETE** `/api/project/{id}`
    - Description: Delete a specific project by its ID.
    - URL Parameter:
        - `id` (integer): The ID of the project to delete.

### Employee Endpoints

- **GET** `/api/employee/`
    - Description: Retrieve a list of all employees.

- **POST** `/api/employee/`
    - Description: Create a new employee.
    - Request Body Example:
      ```json
      {
        "name": "New Employee",
        "companyId": 1
      }
      ```

- **GET** `/api/employee/{id}`
    - Description: Retrieve details of a specific employee by its ID.
    - URL Parameter:
        - `id` (integer): The ID of the employee to retrieve.

- **PUT** `/api/employee/{id}`
    - Description: Update details of a specific employee by its ID.
    - URL Parameter:
        - `id` (integer): The ID of the employee to update.
    - Request Body Example:
      ```json
      {
        "name": "Updated Employee Name"
      }
      ```

- **DELETE** `/api/employee/{id}`
    - Description: Delete a specific employee by its ID.
    - URL Parameter:
        - `id` (integer): The ID of the employee to delete.

## Additional Information

Before running the project, ensure to load the fixtures by running:

```bash
php bin/console doctrine:fixtures:load
```

Ensure that your environment meets all the necessary prerequisites and dependencies as defined in the project documentation. For any issues or questions, please refer to the project's issue tracker or contact the maintainer.
