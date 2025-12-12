package sidibe.cherif.service;

import sidibe.cherif.entity.Complement;
import java.util.List;

public interface ComplementService {
    int creerComplement(Complement complement);
    void modifierComplement(Complement complement);
    void archiverComplement(int id);
    List<Complement> listerComplements();
    List<Complement> listerComplementsActifs();
    List<Complement> listerParType(String type);
    Complement getComplementById(int id);
}
