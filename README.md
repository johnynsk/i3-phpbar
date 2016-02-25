# i3-phpbar

Simple configurable status bar for i3 window manager.

![i3-phpbar demonstration](https://hsto.org/files/a33/ed5/d52/a33ed5d52427408f9d7c14f7e5fbc748.gif)
It outputs every text you want.

Basically it can outputs:

- Currency rate [from micex](http://moex.ru)
- News headers via rss
- Playing music from [moc player](https://wiki.archlinux.org/index.php/Moc)
- Audio volume with alsa
- CO2 level from yocto sensor
- Weather from [ngs](http://pogoda.ngs.ru)
- Unreaded mail [(with imap.py script)](https://github.com/d2one/imap.py)
- Current Jira's task, counted by [jira-agile-worklog-helper](https://github.com/seletskiy/jira-agile-worklog-helper) 

### installation:
    git clone git@github.com:johnynsk/i3-phpbar.git
    vim ~/.i3/config

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
