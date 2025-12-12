package sidibe.cherif.service.impl;

import sidibe.cherif.entity.Complement;
import sidibe.cherif.entity.TypeComplementEnum;
import sidibe.cherif.repository.ComplementRepository;
import sidibe.cherif.service.ComplementService;

import java.util.List;
import java.util.stream.Collectors;

public class ComplementServiceImpl implements ComplementService {
    private final ComplementRepository complementRepository;

    public ComplementServiceImpl(ComplementRepository complementRepository) {
        if (complementRepository == null) {
            throw new IllegalArgumentException("ComplementRepository ne peut pas être null");
        }
        this.complementRepository = complementRepository;
    }

    @Override
    public int creerComplement(Complement complement) {
        return complementRepository.insert(complement);
    }

    @Override
    public void modifierComplement(Complement complement) {
        complementRepository.update(complement);
    }

    @Override
    public void archiverComplement(int id) {
        Complement complement = complementRepository.selectById(id);
        if (complement != null) {
            complement.setArchive(true);
            complementRepository.update(complement);
        }
    }

    @Override
    public List<Complement> listerComplements() {
        return complementRepository.selectAll();
    }

    @Override
    public List<Complement> listerComplementsActifs() {
        return complementRepository.selectAll().stream()
                .filter(complement -> !complement.isArchive())
                .collect(Collectors.toList());
    }

    @Override
    public List<Complement> listerParType(String type) {
        TypeComplementEnum typeEnum = TypeComplementEnum.valueOf(type.toUpperCase());
        return listerComplementsActifs().stream()
                .filter(c -> c.getTypeComplement() == typeEnum)
                .collect(Collectors.toList());
    }

    @Override
    public Complement getComplementById(int id) {
        return complementRepository.selectById(id);
    }
}
