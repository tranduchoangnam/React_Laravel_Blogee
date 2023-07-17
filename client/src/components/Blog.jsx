import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { handleData, handleTitle } from "../utils/handleData.js";
import { useGlobalContext } from "../context";
import axios from "axios";
import backendURL from "../utils/backendUrl";
import Comment from "../components/Comment";
axios.defaults.headers.common["Authorization"] = `Bearer ${localStorage.getItem(
  "token"
)}`;
const Blog = ({ data, type }) => {
  const [deleted, setDeleted] = useState(false);
  const [upvote, setUpvote] = useState(data.countUpvote);
  const [downvote, setDownvote] = useState(data.countDownvote);
  const [voted, setVoted] = useState(data.voted);
  const [toggle, setToggle] = useState(false);
  const { user, token } = useGlobalContext();
  const navigate = useNavigate();
  const handleRead = async (type, url) => {
    if (!type.enable) return;
    if (!user) navigate("/login");
    try {
      await axios.get(`${backendURL}/api/blogs/${data.blog.id}/views`, {
        withCredentials: true,
        headers: { Authorization: `Bearer ${token}` },
      });
    } catch (error) {
      console.log(error);
    }

    navigate(url);
  };
  const handleUpvote = async (type) => {
    if (!type.enable) return;
    if (!user) navigate("/login");
    if (voted === 1) setVoted(0);
    else setVoted(1);
    try {
      const response = await axios.get(
        `${backendURL}/api/blogs/${data.blog.id}/votes/up`,
        {
          withCredentials: true,
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      setUpvote(response.data);
    } catch (error) {
      console.log(error);
    }
  };
  const handleDownvote = async (type) => {
    if (!type.enable) return;
    if (!user) navigate("/login");
    if (voted === -1) setVoted(0);
    else setVoted(-1);
    try {
      const response = await axios.get(
        `${backendURL}/api/blogs/${data.blog.id}/votes/down`,
        {
          withCredentials: true,
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      setDownvote(response.data);
    } catch (error) {
      console.log(error);
    }
  };
  const handleDelete = async (type) => {
    if (!type.enable) return;
    console.log("id", user.id, data.owner.id);

    if (user.id !== data.owner.id) return;
    if (!user) navigate("/login");
    try {
      await axios.delete(`${backendURL}/api/blogs/${data.blog.id}`, {
        withCredentials: true,
        headers: { Authorization: `Bearer ${token}` },
      });
      setDeleted(true);
    } catch (error) {
      console.log(error);
    }
  };
  const handleOptionClick = (event) => {
    setToggle(!toggle);
  };
  const optionBox = (
    <div className="option_box">
      <div className="element " onClick={() => handleDelete(type)}>
        Delete
      </div>
      <div className="element">Share</div>
    </div>
  );

  return (
    <>
      {!deleted && (
        <>
          <div className="blog">
            <div className="blog_header">
              <div className="user_avatar">
                <img src={data.owner.photo} alt="avatar" />
              </div>
              <button
                className="btn_read"
                onClick={() => {
                  handleRead(type, `/blog/${data.blog.id}`);
                }}
              >
                Read
                <i className="bx bxs-right-top-arrow-circle"></i>{" "}
              </button>

              <div
                className="option"
                onClick={() => {
                  handleOptionClick();
                }}
              >
                <i className="bx bx-dots-vertical-rounded"></i>
                {toggle && <>{optionBox}</>}
              </div>
            </div>
            <div className="blog_content">
              <div className="title">{handleTitle(data.blog.title)}</div>
              <div className="time">{data.blog.created_at}</div>
              {!type.preview ? (
                <div className="scroll_content">
                  {handleData(data.blog.content)}
                </div>
              ) : (
                <div className="image">
                  <img src={data.blog.photo} alt="" />
                </div>
              )}
            </div>
            <div className="blog_footer">
              <i
                className={`bx bxs-upvote ${voted === 1 && "highlight"}`}
                onClick={() => handleUpvote(type)}
              >
                {upvote}
              </i>
              <i
                className={`bx bxs-downvote ${voted === -1 && "highlight"}`}
                onClick={() => handleDownvote(type)}
              >
                {downvote}
              </i>
              <i className="bx bxs-comment-detail">{data.countComment}</i>
              {/* <span class="material-symbols-rounded">{data.countShare}</span> */}
              {/* <i className="bx bxs-share">{data.countShare}</i> */}
            </div>
          </div>
        </>
      )}
    </>
  );

  //     )});
};

export default Blog;
