package main;

/**
 * Created by Dmitriy on 21.03.2017.
 */
public class CourierComment extends Comment {

    public CourierComment(int id, String text, String timePosted, User creator, User destinyUser) {
        super(id, text, timePosted, creator, destinyUser);
    }

    public int getCourierCommentByUserId(int id) {}

    public void setCourierComment(Comment comment) {}
}
