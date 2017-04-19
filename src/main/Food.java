package main;

/**
 * Created by NastyaVicodin on 3/18/2017.
 */
public class Food extends Goods {
    public String shelfLife;

    public Food(String name, String type, int id, int count, boolean limitation, String shelfLife) {
        super(name, type, id, count, limitation);
        this.shelfLife = shelfLife;
    }

    public String getShelfLife() {
        return shelfLife;
    }

    public void setShelfLife(String shelfLife) {
        this.shelfLife = shelfLife;
    }
}
