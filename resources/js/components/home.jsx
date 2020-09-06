import React from 'react'
import PropTypes from 'prop-types'
import { List, Drawer, ListItem, ListItemText, CssBaseline, AppBar, Toolbar, Typography, Collapse } from '@material-ui/core'
import { makeStyles } from '@material-ui/core/styles'
import classnames from 'classnames'
import { Switch, Route, NavLink } from 'react-router-dom'
import { Hluttaw } from './pages/pages'
import Countdown from 'react-countdown'

const drawerWidth = 170;

const VoteCountdown = () => {
  const renderer = ({ days, hours, minutes, seconds, completed }) => {
      if (completed) {
        return <Typography style={{fontWeight: 'bold'}} variant='subtitle1'>You gotta go and vote now!</Typography>
      } else {
        return <Typography style={{fontWeight: 'bold'}} variant='subtitle1'>{ `Countdown to Vote - ${days} Day(s), ${hours} Hour(s), ${minutes} Minute(s), ${seconds} Second(s)`}</Typography>
      }
    }
  return (
      <Countdown
      date={new Date(2020,11,8)}
      renderer={renderer}
      />
  )
}


const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
  },
  drawer: {
    width: drawerWidth,
    flexShrink: 0,
  },
  appBar: {
    width: `calc(100% - ${drawerWidth}px)`,
    marginLeft: drawerWidth,
    background: '#ffffff',
    color: '#000000',

  },
  toolbar: {
    minHeight: 45,
  },
  drawerPaper: {
    width: drawerWidth,
    backgroundColor: '#faf7ff',
    variant: 'elevation',
    zIndex: -1
  },
  content: {
    flexGrow: 1,
    padding: theme.spacing(2),
    height: '100vh',
  },
  listItem: {
    height: 25,
    fontWeight: 700,
  },
  listItemText:{
    fontSize:'0.9em',
  },
  nested: {
    paddingLeft: theme.spacing(3.5),
    fontWeight: 900,
  },
}));

const Home = (props) => {
    const classes = useStyles()
    const [selectedIndex, setSelectedIndex] = React.useState(1)
    const routes = {
        1: '/hluttaws/amyotha_hluttaw',
    }
    const [openHluttaws, setOpenHluttaws] = React.useState(true)

    const handleListItemClick = (_, index) => {
        setSelectedIndex(index)
    }
    const drawer = (
      <div>
        <div className={classes.toolbar}></div>
        <List compoennt='nav'>
          <ListItem dense={false} button onClick={() => setOpenHluttaws(!openHluttaws)} className={classes.listItem} >
              <ListItemText primary='Hluttaws' disableTypography className={classes.listItemText} />
          </ListItem>
          <Collapse in={openHluttaws} timeout='auto' unmountOnExit>
          <NavLink to={routes[1]} style={{ textDecoration: 'none', color: (selectedIndex === 1) ? '#383838' : '#9c9c9c' }} key={1} onClick={(event) => handleListItemClick(event, 1)}>
            <ListItem dense={false} button key={1} className={classnames(classes.listItem, classes.nested)} >
              <ListItemText primary={'Amyotha Hluttaw'} disableTypography className={classes.listItemText} />
            </ListItem>
            </NavLink>
          </Collapse>
        </List>
      </div>
    );
    return (
      <div className={classes.root}>
        <CssBaseline />
        <AppBar position="fixed" className={classes.appBar} elevation={0}>
          <Toolbar className={classes.toolbar}>
            <Typography variant="h6" noWrap>
              ElectvisMM
            </Typography>
            <div style={{position: 'absolute', right: 20}}>
            <VoteCountdown />
            </div>
          </Toolbar>
        </AppBar>
        <nav className={classes.drawer} aria-label="mailbox folders">
            <Drawer
              classes={{
                paper: classes.drawerPaper,
              }}
              variant="permanent"
              open
              elevation={0}
            >
              {drawer}
            </Drawer>
        </nav>
        <main className={classes.content}>
          <div className={classes.toolbar} />
          <Switch>
              <Route path='/hluttaws/amyotha_hluttaw'>
              <Hluttaw name='Amyotha Hluttaw' />
              </Route>
              <Route path='/'>
              <Hluttaw name='Amyotha Hluttaw' />
              </Route>
          </Switch>
        </main>
      </div>
    );
}

Home.propTypes = {
    window: PropTypes.func,
};

export default Home