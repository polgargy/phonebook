# Image
ARG NODE_VERSION
FROM node:${NODE_VERSION}-alpine

# Set up work directory
WORKDIR /var/www

# Configure host
ENV HOST 0.0.0.0

# Init command
USER node
CMD ["sh", "-c", "npm install && npm run dev"]
