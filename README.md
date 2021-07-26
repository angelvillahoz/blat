# BLAT

## TL;DR

 - Have `docker-ce`, `docker-compose`, `php`, and `php-cli` installed for the development or test or production environment.
 - If necessary, move `*.sql.gz` dump files into `dumps`. These will be used to automatically initialize the
   mariadb docker instance. **Note that file permissions must be world readable.**
 - Update `.env.dist` by filling out all the fields denoted with `<...>` and save the edited file as `.env`
 - Most processes are handled by the Makefile in conjunction with the configuration information
   contained in the `.env` file.  Run `make help` to list the available targets and a description of what
   they do.
 - Have the necessary PHP libraries installed by executing:
   ```
   make $(cat .env | xargs) vendor-install
   ```
 - Have the necessary JavaScript/ReactJS libraries installed by executing:
   ```
   make $(cat .env | xargs) node_modules-install
   ```
 - The configuration and build process is controlled via a Makekefile and uses the environment defined
   in the `.env` file. Run the following make targets to configure, build, and start the services:
   ```
   make $(cat .env | xargs) configure
   make $(cat .env | xargs) build
   make $(cat .env | xargs) docker-initialize
   ```
 - Have a new database instance empty created by executing:
   ```
   make $(cat .env | xargs) database-initialize
   ```

## Requirements for both production and test environments

- Docker CE 20.10.7+
- Docker Compose 1.27.4+
- PHP 7.4.21+

## Requirements for the development environment besides the previous ones
- DBeaver 21.1.3+
- Go 1.16.6+
- Visual Studio Code 1.58.2+