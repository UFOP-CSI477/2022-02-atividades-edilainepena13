import React from "react";
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";
import Header from "./components/Header";

import StockPage from "./pages/Stock";
import Sells from "./pages/Sells";
import ReportsPage from "./pages/Reports";

function App() {
  return (
    <Router>
        <Header />
        <Switch>
          <Route exact path="/" component={Sells} />
          <Route exact path="/stock" component={StockPage} />
          <Route exact path="/report" component={ReportsPage} />
        </Switch>
      </Router>
  );
}

export default App;
