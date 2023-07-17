import Bar from "../components/Bar";
import Header from "../components/Header";
import { useEffect, useState } from "react";
import backendURL from "../utils/backendUrl";
import Skeleton from "../components/Skeleton";
import axios from "axios";
import BlogList from "../components/BlogList";
import { useGlobalContext } from "../context";
function Following() {
  const [data, setData] = useState();
  const { token } = useGlobalContext();
  useEffect(() => {
    document.title = "Blogee";

    const fetchData = async () => {
      try {
        const response = await axios.get(`${backendURL}/api/following`, {
          withCredentials: true,
          headers: { Authorization: `Bearer ${token}` },
        });
        setData(response.data);
        console.log(response.data);
      } catch (error) {
        console.log(error);
      }
    };
    fetchData();
  }, []);
  if (!data) {
    return <Skeleton />;
  }
  return (
    <>
      {/* {user && <Navigate to="/dashboard" replace={true} />} */}
      <div>
        <Header />
        <Bar />
        <div className="wrapper_right">
          {data.length === 0 ? (
            <Skeleton msg="OMG You have not follow anyone" />
          ) : (
            <BlogList blogs={data} type={{ preview: true, enable: true }} />
          )}
        </div>
      </div>
    </>
  );
}

export default Following;
