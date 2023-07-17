import "./App.css";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import {
  Home,
  Dashboard,
  ProtectedRoute,
  UploadPage,
  FullBlogPage,
  Following,
  History,
} from "./pages/index.js";
import { useGlobalContext } from "./context";
import Skeleton from "./components/Skeleton";
import Login from "./components/Login";
import { useEffect } from "react";
import Cookies from "js-cookie";

function App() {
  const { user, removeUser, saveUser } = useGlobalContext();
  useEffect(() => {
    try {
      const user = JSON.parse(Cookies.get("user"));
      if (user) {
        saveUser(user);
      } else {
        removeUser();
      }
    } catch (error) {
      removeUser();
      console.log(error);
    }
  }, []);
  return (
    <BrowserRouter>
      <Routes>
        <Route exact path="/" element={<Home />}></Route>
        <Route exact path="/login" element={<Login />}></Route>
        <Route exact path="/blog/:blog_id" element={<FullBlogPage />}></Route>
        <Route path="/dashboard/:user_id" element={<Dashboard />}></Route>
        <Route
          path="/following"
          element={
            <ProtectedRoute>
              <Following />
            </ProtectedRoute>
          }
        ></Route>
        <Route
          path="/history"
          element={
            <ProtectedRoute>
              <History />
            </ProtectedRoute>
          }
        ></Route>
        <Route
          exact
          path="/upload"
          element={
            <ProtectedRoute>
              <UploadPage />
            </ProtectedRoute>
          }
        ></Route>
        <Route
          exact
          path="/login/success"
          element={
            <ProtectedRoute>
              <Navigate
                to={user ? `/dashboard/${user.id}` : "/"}
                replace="true"
                element={<Dashboard />}
              ></Navigate>
            </ProtectedRoute>
          }
        ></Route>
        {/* <Route path="*" element={<Error />} /> */}
      </Routes>
    </BrowserRouter>
  );
}

export default App;
