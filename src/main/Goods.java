package main;

/**
 * Created by NastyaVicodin on 3/18/2017.
 */
public class Goods {
    public String name;
    public String type;
    public int id;
    public int count;
    public boolean limitation;

    public boolean AddGoods();
    public boolean DeleteGoods();

    public Goods(String name, String type, int id, int count, boolean limitation) {
        this.name = name;
        this.type = type;
        this.id = id;
        this.count = count;
        this.limitation = limitation;
    }

    public boolean hasLimitation() {
        return limitation;
    }

    public void setLimitation(boolean limitation) {
        this.limitation = limitation;
    }

    public String getName() {
        return name;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getCount() {
        return count;
    }

    public void setCount(int count) {
        this.count = count;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getLimitation() {
        return limitation;
    }

    public void setLimitation(String limitation) {
        this.limitation = limitation;
    }
}
