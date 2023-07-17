import axios from "axios";
import React, { useContext, useState } from "react";
import backendURL from "./utils/backendUrl";
import Cookies from "js-cookie";

const UserContext = React.createContext();
const UserProvider = ({ children }) => {
  const [isLoading, setIsLoading] = useState(true);
  const [user, setUser] = useState(null);
  // const [token, setToken] = useState(null);

  const saveUser = (user) => {
    console.log(user);
    setUser(user);
    Cookies.set("user", JSON.stringify(user));
  };
  // const saveToken = (token) => {
  //   setToken(token);
  //   console.log(token);
  //   Cookies.set("token", JSON.stringify(token));
  // };
  const removeUser = () => {
    setUser(null);
    // setToken(null);
    Cookies.remove("user");
    Cookies.remove("token");
  };

  // const fetchUser = async () => {
  //   try {
  //     const { data } = await axios.get(`${backendURL}/api/current_user`, {
  //       withCredentials: true,
  //       headers: { Authorization: `Bearer ${Cookies.get("token")}` },
  //     });
  //     saveUser(data);
  //     console.log(data);
  //   } catch (error) {
  //     console.log(error);
  //     console.log("1");
  //     removeUser();
  //   }
  //   setIsLoading(false);
  // };

  const logoutUser = async () => {
    try {
      await axios.delete(`${backendURL}/api/auth/signout`, {
        withCredentials: true,
        headers: { Authorization: `Bearer ${Cookies.get("token")}` },
      });
      removeUser();
    } catch (error) {
      console.log(error);
    }
  };

  return (
    <UserContext.Provider
      value={{
        isLoading,
        saveUser,
        user,
        logoutUser,
        removeUser,
        setIsLoading,
      }}
    >
      {children}
    </UserContext.Provider>
  );
};
// make sure use
export const useGlobalContext = () => {
  return useContext(UserContext);
};

export { UserProvider };
