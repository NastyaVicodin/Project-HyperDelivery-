package main;

/**
 * Created by Dmitriy on 21.03.2017.
 */
public class ClientComment extends Comment {

    public ClientComment(int id, String text, String timePosted, User creator, User destinyUser) {
        super(id, text, timePosted, creator, destinyUser);
    }

    public int getClientCommentByUserId(int id) {
    }

    public void setClientComment(Comment comment) {
    }
}
