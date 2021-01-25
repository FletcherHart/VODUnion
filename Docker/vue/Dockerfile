FROM node:lts-alpine
WORKDIR /var/code/
COPY . ./
RUN npm install
EXPOSE 8080
RUN npm run production
# CMD ["npm", "run", "dev"]

#docker cp contName:/var/code/node_modules ./