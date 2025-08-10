FROM node:20-alpine

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN chmod -R 755 /app

ENV HOST=0.0.0.0
ENV PORT=3000
ENV CHOKIDAR_USEPOLLING=true

EXPOSE 3000 24678

CMD ["npm", "run", "dev"]