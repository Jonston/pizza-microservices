FROM node:20-alpine

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN chmod -R 755 /app

EXPOSE 3000 24678

CMD ["npm", "run", "dev"]