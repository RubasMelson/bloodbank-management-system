FROM php:8.2-cli

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Expose port Render expects
EXPOSE 8080

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "."]
