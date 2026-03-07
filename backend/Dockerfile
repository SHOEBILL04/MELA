# Backend Dockerfile
FROM node:18-alpine

WORKDIR /app

# Install dependencies first (for better caching)
COPY package*.json ./
RUN npm install

# Copy source code
COPY . .

# Expose the port (backend server.js e port 5000 thaka dorkar)
EXPOSE 5000

# Start the application
CMD ["npm", "run", "dev"]
