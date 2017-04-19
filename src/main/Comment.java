package main;

/**
 * Created by Dmitriy on 19.03.2017.
 */
abstract class Comment {
    public int id;
    public String text;
    public String timePosted;
    public User creator;
    public User destinyUser;

    public Comment(int id, String text, String timePosted, User creator, User destinyUser) {
        this.id = id;
        this.text = text;
        this.timePosted = timePosted;
        this.creator = creator;
        this.destinyUser = destinyUser;
    }

    abstract public Comment addComment();
    abstract public boolean deleteComment(int commentId);
    abstract public boolean editComment(int commentId);
    public Comment getCommentById(int commentId);

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getText() {
        return text;
    }

    public void setText(String text) {
        this.text = text;
    }

    public String getTimePosted() {
        return timePosted;
    }

    public void setTimePosted(String timePosted) {
        this.timePosted = timePosted;
    }

    public int getCreatorId() {
        return creatorId;
    }

    public void setCreatorId(int creatorId) {
        this.creatorId = creatorId;
    }
}
