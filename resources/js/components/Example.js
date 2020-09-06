import React from 'react';
import ReactDOM from 'react-dom';
import Home from './home'
import { BrowserRouter as Router } from 'react-router-dom'
import { createMuiTheme, MuiThemeProvider } from '@material-ui/core'

const theme = createMuiTheme({
    props: {
        MuiButtonBase: {
            disableRipple: true,
        },
    },

});

function Example() {
    return (
        <MuiThemeProvider theme={theme}>
            <Router>
                <Home />
            </Router>
        </MuiThemeProvider>
    );
}

export default Example;

if (document.getElementById('index')) {
    ReactDOM.render(<Example />, document.getElementById('index'));
}
