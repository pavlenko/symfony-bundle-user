### Configure firewall
```yaml
security:
    firewalls:
        my_firewall:
            # add these lines to use LoginForm type
            form_login:
                login_path: /login/
                username_parameter:   'pe_user__login[username]'
                password_parameter:   'pe_user__login[password]'
                csrf_parameter:       'pe_user__login[_token]'
                csrf_token_generator: security.csrf.token_manager
                csrf_token_id:        authenticate
```
