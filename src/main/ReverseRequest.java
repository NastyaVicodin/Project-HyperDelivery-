package main;

/**
 * Created by Dmitriy on 19.03.2017.
 */
public class ReverseRequest extends Request{
    public int status;

    public reverseRequest(int id, int status) {
        this.id = id;
        this.status = status;
    }

    public boolean confirmRequest(int requestId = null);
}
