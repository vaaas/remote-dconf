# remote-dconf

Allows access to a remote server's dconf database. That way, a service can query a central configuration server for its configuration, for example in a microservices architecture.

Access is only allowed with authentication. Only HTTP basic authentication is supported.

Using the defaults, you can acquire a dump of the entire dconf database as such:

`curl http://test@0.0.0.0:8000/`

You should also consult the [dconf manpage](https://manpages.ubuntu.com/manpages/bionic/man1/dconf.1.html).

# Requirements

- PHP >= 8.0
- bash
- dconf-cli

# Installation

`remote-dconf` is expected to be installed in `/opt/remote-dconf`.

For ease of use, `build.sh` will generate a `deb`, which you can then install and manage with `dpkg`.

# Running

`start.sh` will initialise some environment variables, then run `index.php` with the PHP built-in web server. (`php -S`)

For ease of use, you will find a systemd service in `etc/remote-dconf.service`, so you can manage the server with systemd.

# Configuration

There are three things to configure: the dconf database to read, the port, and the authorisation token.

- database: the dconf database of the user the process is running as will be used. All the magic is handled by `dconf`. You can find the dconf database at `~/.config/dconf/user`.
- port: configured in the dconf entry `/org/vas/remote-dconf/port`. If not found, defaults to `8000`.
- token: configured in the dconf entry `/org/vas/remote-dconf/token`. If not found, defaults to `test`.

# Architecture

Code is kept intentionally minimal and avoids external dependencies. `remote-dconf` is written in barebones PHP; composer is not used.

You will find some utility classes in `lib`:

- `request.php` provides a nicer API to PHP request data, which is normally stored in globals.
- `response.php` provides a nicer API for serving responses. Responses serve themselves through the `serve()` method.

`index.php` performs the business logic, which should be self-explanatory.

There is no PHP dconf library. Instead, we use the `dconf` binary and pipe its output to our response. To reduce unnecessary forking, our authentication token is loaded into an environment variable.

We use the PHP built-in server, which doesn't have the best performance, but it can manage a few hundred requests per second. For the purposes of this project, this should suffice.

If you want encryption, you should use a reverse proxy.
