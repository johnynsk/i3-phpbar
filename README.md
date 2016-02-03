# i3-phpbar

### Установка:
~/.i3/config:
```
    
    # some config before
    bar {

        status_command php ~/.i3/i3-phpbar/bar.php
        position bottom

        colors {
            background #222250
            statusline #dfd5c8

            inactive_workspace #dfd5c8 #222222
        }
        
        workspace_buttons yes
    }
    # some config after
```

### Плагин для почты

* https://github.com/d2one/imap.py
