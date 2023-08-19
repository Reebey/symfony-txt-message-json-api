# Symfony Text Message JSON API

This API is designed for storing text messages.

## Project Structure

- **src**: Contains the Symfony API project.
- **vue-ui**: A Vue 3 JavaScript application based on the DevExpress Vue template. It interacts with the src API.
- **build**: Configuration files.
- **Dockerfiles** and **docker-compose.yml**: Dockerization files for the app.

## Getting Started

To run the project, make sure you have Docker Compose installed, then execute:

```shell
docker-compose up -d
```

The app will be accessible at [http://localhost:8080](http://localhost:8080).

## API Endpoints

- **GET** `/api/messages?sort_by=[uuid|message|createdAt]&order_by=[asc|desc]`: Retrieves a list of all messages.
- **GET** `/api/messages/{uuid}`: Fetches a message by UUID.
- **POST** `/api/messages`: Creates a new message and returns the UUID of the newly created message.

## User Interface (UI)

Access the UI at [http://localhost:8080/messages](http://localhost:8080/messages):

- Displays a table (dxDataTable) of messages.
- Clicking on a row opens a details popup (dxPopup).
- "CREATE NEW MESSAGE" button opens a form within a dxPopup for creating a new message.

This project utilizes Docker, Symfony, Vue 3, and the DevExpress Vue template.
