<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Modules;

class DependencyGraph
{
    /**
     * @var array<class-string, class-string[]>
     */
    private array $dependencies = [];

    /**
     * @param class-string $targetClass
     * @param class-string[] $dependencyClasses
     * @throws \RuntimeException when circular dependency is found
     */
    public function requires(string $targetClass, array $dependencyClasses): void
    {
        foreach ($dependencyClasses as $class) {
            if ($this->hasCircularDependency($class, $targetClass)) {
                throw new \RuntimeException("Circular dependency found for $class and $targetClass");
            }
        }

        $this->dependencies[$targetClass] = $dependencyClasses;
    }

    /**
     * Returns an array with all classes that the target class depends on.
     *
     * @param class-string $targetClass
     * @return class-string[]
     */
    public function dependencies(string $targetClass): array
    {
        return $this->dependencies[$targetClass] ?? [];
    }

    /**
     * Returns an array with all classes that depend on the target class.
     *
     * @param class-string $targetClass
     * @return class-string[]
     */
    public function dependents(string $targetClass): array
    {
        $result = [];

        foreach ($this->dependencies as $class => $dependencies) {
            if (in_array($targetClass, $dependencies)) {
                $result[] = $class;
            }
        }

        return $result;
    }

    /**
     * Returns a topologically sorted array of classes, where each class depends only on classes appearing earlier in the array.
     *
     * @return class-string[]
     */
    public function getTopologicalSorted(): array
    {
        $visited = [];
        $sorted = [];

        foreach (array_keys($this->dependencies) as $class) {
            if (!isset($visited[$class])) {
                $this->visit($class, $visited, $sorted);
            }
        }

        return $sorted;
    }

    protected function visit(string $class, array &$visited, array &$sorted): void
    {
        $visited[$class] = true;

        foreach ($this->dependencies($class) as $dependency) {
            if (!isset($visited[$dependency])) {
                $this->visit($dependency, $visited, $sorted);
            }
        }

        $sorted[] = $class;
    }

    protected function hasCircularDependency(string $start, string $end): bool
    {
        if ($start === $end) {
            return true;
        }

        if (!isset($this->dependencies[$start])) {
            return false;
        }

        foreach ($this->dependencies[$start] as $class) {
            if ($class === $end || $this->hasCircularDependency($class, $end)) {
                return true;
            }
        }

        return false;
    }
}
