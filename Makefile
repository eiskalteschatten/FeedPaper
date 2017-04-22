start:
	bash -c "trap 'docker-compose down' EXIT; docker-compose up"

rebuild:
	bash -c "trap 'docker-compose down' EXIT; docker-compose up --build"