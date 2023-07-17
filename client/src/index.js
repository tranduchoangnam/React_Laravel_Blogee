import React from "react";
import ReactDOM from "react-dom/client";
import "./index.css";
import App from "./App";
import reportWebVitals from "./reportWebVitals";
import { UserProvider } from "./context";
import { ToastProvider } from "@hanseo0507/react-toast";
import axios from "axios";

const root = ReactDOM.createRoot(document.getElementById("root"));

root.render(
  //<React.StrictMode>
  <UserProvider>
    <ToastProvider>
      <App />
    </ToastProvider>
  </UserProvider>
  //</React.StrictMode>
);
